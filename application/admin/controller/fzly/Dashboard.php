<?php

namespace app\admin\controller\fzly;

use app\common\controller\Backend;
use think\Db;
use fast\Date;

/**
 * 景区数据大屏
 *
 * @icon fa fa-dashboard
 */
class Dashboard extends Backend
{
    /**
     * 查看数据大屏
     */
    public function index()
    {
        // 获取今日数据
        $todayData = $this->getTodayDashboardData();

        // 获取今日时段数据（用于图表）
        $hourlyData = $this->getHourlyData();

        // 获取客源地分布
        $sourceData = $this->getSourceAreaData();

        $this->view->assign([
            'today' => date('Y-m-d'),
            'todayData' => $todayData,
            'hourlyData' => $hourlyData,
            'sourceData' => $sourceData
        ]);

        return $this->view->fetch('dashboard');
    }

    /**
     * 获取今日大屏核心数据
     */
    private function getTodayDashboardData()
    {
        $today = date('Y-m-d');
        $now = date('Y-m-d H:i:s');

        // 今日总入场人数统计
        $entryData = Db::name('fzly_visitor_report')
            ->where('date', 'between time', [$today . ' 00:00:00', $now])
            ->field('SUM(entry_num) as total_entry, 
                    SUM(staff_entry) as staff, 
                    SUM(onsite_ticket) as onsite, 
                    SUM(ota_ticket) as ota, 
                    SUM(official_ticket) as official')
            ->find();

        // 今日总出场人数
        $exitNum = Db::name('fzly_visitor_report')
            ->where('date', 'between time', [$today . ' 00:00:00', $now])
            ->sum('exit_num');

        // 当前在场人数（取最新记录）
        $lastRecord = Db::name('fzly_visitor_report')
            ->where('date', 'between time', [$today . ' 00:00:00', $now])
            ->order('date', 'desc')
            ->find();
        $currentNum = $lastRecord ? $lastRecord['current_num'] : 0;

        // 承载量信息（取今日最新设置）
        $capacity = Db::name('fzly_visitor_report')
            ->where('date', 'between time', [$today . ' 00:00:00', $now])
            ->order('date', 'desc')
            ->field('max_capacity, instant_max_capacity')
            ->find();

        return [
            'total_entry' => $entryData['total_entry'] ?? 0,
            'staff_entry' => $entryData['staff'] ?? 0,
            'onsite_ticket' => $entryData['onsite'] ?? 0,
            'ota_ticket' => $entryData['ota'] ?? 0,
            'official_ticket' => $entryData['official'] ?? 0,
            'exit_num' => $exitNum ?? 0,
            'current_num' => $currentNum,
            'max_capacity' => $capacity['max_capacity'] ?? 0,
            'instant_max_capacity' => $capacity['instant_max_capacity'] ?? 0,
            'capacity_usage' => $capacity['max_capacity'] ?
                round(($entryData['total_entry'] / $capacity['max_capacity']) * 100, 2) : 0
        ];
    }

    /**
     * 获取今日时段数据
     */
    private function getHourlyData()
    {
        $today = date('Y-m-d');
        $hours = [];
        $data = [];

        // 生成今日小时数组
        for ($i = 0; $i < 24; $i++) {
            $hours[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
        }

        // 正确示例：使用非保留字作为别名
        $hourlyData = Db::name('fzly_visitor_report')
            ->field("HOUR(date) as hour, SUM(entry_num) as entry_total, SUM(exit_num) as exit_total")
            ->where('date', 'like', '2025-11-25%')
            ->group('hour')
            ->select();

        // 初始化数据数组
        foreach ($hours as $hour) {
            $data['time'][] = $hour;
            $data['entry'][] = 0;
            $data['exit'][] = 0;
        }

        // 填充数据
        foreach ($hourlyData as $item) {
            $index = (int)$item['hour'];
            $data['entry'][$index] = $item['entry_total'];
            $data['exit'][$index] = $item['exit_total'];
        }

        return $data;
    }

    /**
     * 获取客源地分布数据
     */
    private function getSourceAreaData()
    {
        $today = date('Y-m-d');
        $sourceData = [
            'area' => [],
            'num' => []
        ];

        // 获取今日最新客源地数据
        $lastRecord = Db::name('fzly_visitor_report')
            ->where('date', 'like', $today . '%')
            ->where('source_area', '<>', '')
            ->order('date', 'desc')
            ->find();

        if ($lastRecord && !empty($lastRecord['source_area'])) {
            $areas = json_decode($lastRecord['source_area'], true);
            if (is_array($areas)) {
                foreach ($areas as $area => $num) {
                    $sourceData['area'][] = $area;
                    $sourceData['num'][] = $num;
                }
            }
        }

        return $sourceData;
    }
}