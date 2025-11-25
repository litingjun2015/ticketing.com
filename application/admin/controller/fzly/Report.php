<?php

namespace app\admin\controller\fzly;

use app\common\controller\Backend;
use think\Db;
use fast\Date;
use PHPExcel;
use PHPExcel_IOFactory;
use think\Exception;
use think\Log;

/**
 * 报表管理
 *
 * @icon fa fa-bar-chart
 */
class Report extends Backend
{
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->view->assign("channelList", [
            'all' => __('All channels'),
            'offline' => __('Offline window'),
            'ota' => __('OTA'),
            'mini_program' => __('Mini program'),
            'official' => __('Official')
        ]);
        $this->view->assign("typeList", [
            'daily' => __('Daily'),
            'weekly' => __('Weekly'),
            'monthly' => __('Monthly'),
            'yearly' => __('Yearly')
        ]);
        $this->view->assign("reportTypeList", [
            'sales' => __('Sales report'),
            'stock' => __('Stock report'),
            'visitor' => __('Visitor report'),
            'device' => __('Device report')
        ]);
    }

    /**
     * 报表首页
     */
    public function index()
    {
        return $this->view->fetch();
    }

    /**
     * 销售报表
     */
    public function sales()
    {
        $type = $this->request->param('type', 'daily');
        $start_date = $this->request->param('start_date', date('Y-m-d', strtotime('-7 days')));
        $end_date = $this->request->param('end_date', date('Y-m-d'));
        $channel = $this->request->param('channel', 'all');
        $product_type = $this->request->param('product_type', 'all');

        $this->view->assign([
            'type' => $type,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'channel' => $channel,
            'product_type' => $product_type,
            'productTypeList' => $this->getProductTypeList()
        ]);

        return $this->view->fetch();
    }

    /**
     * 客流报表
     */
    public function visitor()
    {
        $type = $this->request->param('type', 'daily');
        $start_date = $this->request->param('start_date', date('Y-m-d', strtotime('-7 days')));
        $end_date = $this->request->param('end_date', date('Y-m-d'));

        $this->view->assign([
            'type' => $type,
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);

        return $this->view->fetch();
    }

    /**
     * 设备运行报表
     */
    public function device()
    {
        $type = $this->request->param('type', 'daily');
        $start_date = $this->request->param('start_date', date('Y-m-d', strtotime('-7 days')));
        $end_date = $this->request->param('end_date', date('Y-m-d'));
        $device_type = $this->request->param('device_type', 'all');

        $this->view->assign([
            'type' => $type,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'device_type' => $device_type,
            'deviceTypeList' => $this->getDeviceTypeList()
        ]);

        return $this->view->fetch();
    }

    /**
     * 自定义报表
     */
    public function custom()
    {
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = Db::name('fzly_custom_report')
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = Db::name('fzly_custom_report')
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 报表定时发送配置
     */
    public function schedule()
    {
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = Db::name('fzly_report_schedule')
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = Db::name('fzly_report_schedule')
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 景区数据大屏
     */
    public function dashboard()
    {
        // 获取今日数据
        $today = date('Y-m-d');
        $todayData = $this->getTodayDashboardData();

        $this->view->assign([
            'today' => $today,
            'data' => $todayData
        ]);

        return $this->view->fetch();
    }

    /**
     * 获取销售报表数据
     */
    public function getSalesData()
    {
        $type = $this->request->param('type', 'daily');
        $start_date = $this->request->param('start_date');
        $end_date = $this->request->param('end_date');
        $channel = $this->request->param('channel', 'all');
        $product_type = $this->request->param('product_type', 'all');

        // 步骤1：转换日期格式（将/替换为-）
        $startDate = str_replace('/', '-',$start_date);
        $endDate = str_replace('/', '-', $end_date);

        // 构建查询条件
        $where = [];

        // 步骤2：构造二维索引数组，转义关键字段`date`
        $where = [
            ['`date`', 'between', [$startDate, $endDate]]
        ];

        if ($channel != 'all') {
            $where[] = ['channel', '=', $channel];
        }

        if ($product_type != 'all') {
            $where[] = ['product_type', '=', $product_type];
        }

        // 按类型处理数据
        $data = [];
        switch ($type) {
            case 'daily':
                $data = $this->handleDailySalesData($where, $start_date, $end_date);
                break;
            case 'weekly':
                $data = $this->handleWeeklySalesData($where, $start_date, $end_date);
                break;
            case 'monthly':
                $data = $this->handleMonthlySalesData($where, $start_date, $end_date);
                break;
            case 'yearly':
                $data = $this->handleYearlySalesData($where, $start_date, $end_date);
                break;
        }

        return json(['code' => 0, 'data' => $data]);
    }

    /**
     * 获取客流报表数据
     */
    public function getVisitorData()
    {
        $type = $this->request->param('type', 'daily');
        $start_date = $this->request->param('start_date');
        $end_date = $this->request->param('end_date');

        // 步骤1：转换日期格式（将/替换为-）
        $startDate = str_replace('/', '-',$start_date);
        $endDate = str_replace('/', '-', $end_date);

        // 构建查询条件
        $where = [];

        // 步骤2：构造二维索引数组，转义关键字段`date`
        $where = [
            ['`date`', 'between', [$startDate, $endDate]]
        ];

//        var_dump($where);

        // 按类型处理数据
        $data = [];
        switch ($type) {
            case 'daily':
                $data = $this->handleDailyVisitorData($where, $start_date, $end_date);
                Log::info($data);
                break;
            case 'weekly':
                $data = $this->handleWeeklyVisitorData($where, $start_date, $end_date);
                break;
            case 'monthly':
                $data = $this->handleMonthlyVisitorData($where, $start_date, $end_date);
                break;
            case 'yearly':
                $data = $this->handleYearlyVisitorData($where, $start_date, $end_date);
                break;
        }

        return json(['code' => 0, 'data' => $data]);
    }

    /**
     * 获取设备报表数据
     */
    public function getDeviceData()
    {
        $type = $this->request->param('type', 'daily');
        $start_date = $this->request->param('start_date');
        $end_date = $this->request->param('end_date');
        $device_type = $this->request->param('device_type', 'all');

        // 构建查询条件
        $where = [];
        $where[] = ['date', 'between time', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']];

        if ($device_type != 'all') {
            $where[] = ['device_type', '=', $device_type];
        }

        // 按类型处理数据
        $data = [];
        switch ($type) {
            case 'daily':
                $data = $this->handleDailyDeviceData($where, $start_date, $end_date);
                break;
            case 'weekly':
                $data = $this->handleWeeklyDeviceData($where, $start_date, $end_date);
                break;
            case 'monthly':
                $data = $this->handleMonthlyDeviceData($where, $start_date, $end_date);
                break;
        }

        return json(['code' => 0, 'data' => $data]);
    }

    /**
     * 导出报表
     */
    public function export()
    {
        $report_type = $this->request->param('report_type');
        $format = $this->request->param('format', 'excel');
        $type = $this->request->param('type', 'daily');
        $start_date = $this->request->param('start_date');
        $end_date = $this->request->param('end_date');
        $params = $this->request->param();

        // 根据报表类型获取数据
        switch ($report_type) {
            case 'sales':
                $data = $this->getExportSalesData($type, $start_date, $end_date, $params);
                $title = __('Sales report');
                break;
            case 'stock':
                $data = $this->getExportStockData($type, $start_date, $end_date, $params);
                $title = __('Stock report');
                break;
            case 'visitor':
                $data = $this->getExportVisitorData($type, $start_date, $end_date, $params);
                $title = __('Visitor report');
                break;
            case 'device':
                $data = $this->getExportDeviceData($type, $start_date, $end_date, $params);
                $title = __('Device report');
                break;
            default:
                return json(['code' => 1, 'msg' => __('Invalid report type')]);
        }

        // 导出数据
        if ($format == 'excel') {
            return $this->exportToExcel($title, $data['fields'], $data['list']);
        } elseif ($format == 'pdf') {
            return $this->exportToPdf($title, $data['fields'], $data['list']);
        } else {
            return json(['code' => 1, 'msg' => __('Invalid format')]);
        }
    }

    /**
     * 添加自定义报表
     */
    public function addCustom()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post();

            $data = [
                'title' => $params['title'],
                'data_source' => $params['data_source'],
                'fields' => json_encode($params['fields']),
                'filters' => isset($params['filters']) ? json_encode($params['filters']) : '',
                'sort' => isset($params['sort']) ? json_encode($params['sort']) : '',
                'user_id' => $this->auth->id,
                'is_public' => isset($params['is_public']) ? $params['is_public'] : 0,
                'status' => 1,
                'createtime' => time(),
                'updatetime' => time()
            ];

            $result = Db::name('fzly_custom_report')->insert($data);
            if ($result) {
                return json(['code' => 0, 'msg' => __('Success')]);
            } else {
                return json(['code' => 1, 'msg' => __('Failed')]);
            }
        }

        $this->view->assign('dataSources', [
            'sales' => __('Sales data'),
            'stock' => __('Stock data'),
            'visitor' => __('Visitor data'),
            'device' => __('Device data')
        ]);

        return $this->view->fetch();
    }

    /**
     * 获取今日大屏数据
     */
    private function getTodayDashboardData()
    {
        $today = date('Y-m-d');
        $now = date('Y-m-d H:i:s');

        // 今日总入场人数
        $entryData = Db::name('fzly_visitor_report')
            ->where('date', 'between time', [$today . ' 00:00:00', $now])
            ->field('SUM(entry_num) as total_entry, SUM(staff_entry) as staff, SUM(onsite_ticket) as onsite, SUM(ota_ticket) as ota, SUM(official_ticket) as official')
            ->find();

        // 今日总出场人数
        $exitNum = Db::name('fzly_visitor_report')
            ->where('date', 'between time', [$today . ' 00:00:00', $now])
            ->sum('exit_num');

        // 当前在场人数
        $lastRecord = Db::name('fzly_visitor_report')
            ->where('date', 'between time', [$today . ' 00:00:00', $now])
            ->order('date', 'desc')
            ->find();
        $currentNum = $lastRecord ? $lastRecord['current_num'] : 0;

        // 承载量信息
        $capacity = Db::name('fzly_visitor_report')
            ->where('date', 'between time', [$today . ' 00:00:00', $now])
            ->field('max_capacity, instant_max_capacity')
            ->find();

        return [
            'staff_entry' => $entryData['staff'] ?? 0,
            'onsite_ticket' => $entryData['onsite'] ?? 0,
            'ota_ticket' => $entryData['ota'] ?? 0,
            'official_ticket' => $entryData['official'] ?? 0,
            'total_entry' => $entryData['total_entry'] ?? 0,
            'total_exit' => $exitNum ?? 0,
            'current_num' => $currentNum,
            'max_capacity' => $capacity['max_capacity'] ?? 5000,
            'instant_max_capacity' => $capacity['instant_max_capacity'] ?? 2000
        ];
    }

    /**
     * 处理日销售数据
     */
    private function handleDailySalesData($where, $start_date, $end_date)
    {
//        $list = Db::name('fzly_sales_report')
//            ->where($where)
//            ->field('date, product_name, channel, SUM(sales_num) as sales_num, SUM(sales_amount) as sales_amount, SUM(refund_num) as refund_num, SUM(refund_amount) as refund_amount, SUM(actual_sales) as actual_sales')
//            ->group('date, product_name, channel')
//            ->select();

        $list = Db::name('fzly_sales_report')
            ->whereBetween('date', [$start_date, $end_date]) // 快捷方法，无需手动写between
            ->field('`date`, product_name, channel, SUM(sales_num) as sales_num, SUM(sales_amount) as sales_amount, SUM(refund_num) as refund_num, SUM(refund_amount) as refund_amount, SUM(actual_sales) as actual_sales')
            ->group('`date`, product_name, channel') // date是关键字，需转义
            ->select();

        // 处理图表数据
        $dates = $this->getDateRange($start_date, $end_date);
        $chartData = [
            'categories' => $dates,
            'total_sales' => array_fill_keys($dates, 0),
            'channel_sales' => [
                'offline' => array_fill_keys($dates, 0),
                'ota' => array_fill_keys($dates, 0),
                'mini_program' => array_fill_keys($dates, 0),
                'official' => array_fill_keys($dates, 0)
            ]
        ];

        foreach ($list as $item) {
            if (in_array($item['date'], $dates)) {
                $chartData['total_sales'][$item['date']] += $item['actual_sales'];

                if (isset($chartData['channel_sales'][$item['channel']])) {
                    $chartData['channel_sales'][$item['channel']][$item['date']] += $item['actual_sales'];
                }
            }
        }

        // 转换为数组
        foreach ($chartData['total_sales'] as $key => $value) {
            $chartData['total_sales_array'][] = $value;
        }

        foreach ($chartData['channel_sales'] as $channel => $values) {
            foreach ($values as $value) {
                $chartData['channel_sales_array'][$channel][] = $value;
            }
        }

        return [
            'tableData' => $list,
            'chartData' => $chartData
        ];
    }

    /**
     * 导出到Excel
     */
    private function exportToExcel($title, $fields, $data)
    {
        try {
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setCreator("System")
                ->setLastModifiedBy("System")
                ->setTitle($title)
                ->setSubject($title)
                ->setDescription($title)
                ->setKeywords($title)
                ->setCategory($title);

            // 设置表头
            $columnIndex = 0;
            foreach ($fields as $field => $name) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($columnIndex, 1, $name);
                $columnIndex++;
            }

            // 填充数据
            $rowIndex = 2;
            foreach ($data as $item) {
                $columnIndex = 0;
                foreach ($fields as $field => $name) {
                    $value = isset($item[$field]) ? $item[$field] : '';
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($columnIndex, $rowIndex, $value);
                    $columnIndex++;
                }
                $rowIndex++;
            }

            $objPHPExcel->getActiveSheet()->setTitle($title);
            $objPHPExcel->setActiveSheetIndex(0);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $title . '_' . date('YmdHis') . '.xlsx"');
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
        } catch (Exception $e) {
            return json(['code' => 1, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * 导出到PDF
     */
    private function exportToPdf($title, $fields, $data)
    {
        // PDF导出实现，需要引入TCPDF或其他PDF库
        return json(['code' => 0, 'msg' => 'PDF export function will be implemented later']);
    }

    /**
     * 获取日期范围数组
     */
    private function getDateRange($start, $end)
    {
        $dates = [];
        $current = strtotime($start);
        $end = strtotime($end);

        while ($current <= $end) {
            $dates[] = date('Y-m-d', $current);
            $current = strtotime('+1 day', $current);
        }

        return $dates;
    }

    /**
     * 获取产品类型列表
     */
    private function getProductTypeList()
    {
        return [
            'all' => __('All types'),
            'ticket' => __('Ticket'),
            'package' => __('Package'),
            'service' => __('Service')
        ];
    }

    /**
     * 获取设备类型列表
     */
    private function getDeviceTypeList()
    {
        return [
            'all' => __('All types'),
            'gate' => __('Gate machine'),
            'ticket_machine' => __('Ticket machine'),
            'monitor' => __('Monitor'),
            'scanner' => __('Scanner')
        ];
    }

    /**
     * 处理周销售数据
     */
    /**
     * 处理周销售数据
     */
    private function handleWeeklySalesData($where, $start_date, $end_date)
    {
        // 修复：移除SELECT中的`date`字段，仅保留DATE_FORMAT生成的week
        $list = Db::name('fzly_sales_report')
            ->whereBetween('date', [$start_date, $end_date])
            ->field(Db::raw("DATE_FORMAT(`date`, '%Y-%u') as week, 
         product_name, 
         channel, 
         SUM(sales_num) as sales_num, 
         SUM(sales_amount) as sales_amount, 
         SUM(refund_num) as refund_num, 
         SUM(refund_amount) as refund_amount, 
         SUM(actual_sales) as actual_sales")
            )
            ->group("week, product_name, channel")
            ->select();

        // 后续代码保持不变...
        $weeks = $this->getWeekRange($start_date, $end_date);

        // 初始化图表数据
        $chartData = [
            'categories' => $weeks,
            'total_sales' => array_fill_keys($weeks, 0),
            'channel_sales' => [
                'offline' => array_fill_keys($weeks, 0),
                'ota' => array_fill_keys($weeks, 0),
                'mini_program' => array_fill_keys($weeks, 0),
                'official' => array_fill_keys($weeks, 0)
            ]
        ];

        // 填充图表数据
        foreach ($list as $item) {
            $week = $item['week'];
            if (in_array($week, $weeks)) {
                $chartData['total_sales'][$week] += $item['actual_sales'];

                if (isset($chartData['channel_sales'][$item['channel']])) {
                    $chartData['channel_sales'][$item['channel']][$week] += $item['actual_sales'];
                }
            }
        }

        // 转换为数组格式
        $chartData['total_sales_array'] = array_values($chartData['total_sales']);
        foreach ($chartData['channel_sales'] as $channel => $values) {
            $chartData['channel_sales_array'][$channel] = array_values($values);
        }

        return [
            'tableData' => $list,
            'chartData' => $chartData
        ];
    }

    /**
     * 处理月销售数据
     */
    private function handleMonthlySalesData($where, $start_date, $end_date)
    {
        // 查询月销售数据，按月份、产品名称、渠道分组
        $list = Db::name('fzly_sales_report')
            ->whereBetween('date', [$start_date, $end_date])
            // 修复后
            ->field(Db::raw("DATE_FORMAT(`date`, '%Y-%m') as month, 
                 product_name, 
                 channel, 
                 SUM(sales_num) as sales_num, 
                 SUM(sales_amount) as sales_amount, 
                 SUM(refund_num) as refund_num, 
                 SUM(refund_amount) as refund_amount, 
                 SUM(actual_sales) as actual_sales")
            )
            ->group("month, product_name, channel")
            ->select();

        // 获取日期范围内的所有月份（格式：年-月，如2024-01）
        $months = $this->getMonthRange($start_date, $end_date);

        // 初始化图表数据
        $chartData = [
            'categories' => $months,
            'total_sales' => array_fill_keys($months, 0),
            'channel_sales' => [
                'offline' => array_fill_keys($months, 0),
                'ota' => array_fill_keys($months, 0),
                'mini_program' => array_fill_keys($months, 0),
                'official' => array_fill_keys($months, 0)
            ]
        ];

        // 填充图表数据
        foreach ($list as $item) {
            $month = $item['month'];
            if (in_array($month, $months)) {
                $chartData['total_sales'][$month] += $item['actual_sales'];

                if (isset($chartData['channel_sales'][$item['channel']])) {
                    $chartData['channel_sales'][$item['channel']][$month] += $item['actual_sales'];
                }
            }
        }

        // 转换为数组格式
        $chartData['total_sales_array'] = array_values($chartData['total_sales']);
        foreach ($chartData['channel_sales'] as $channel => $values) {
            $chartData['channel_sales_array'][$channel] = array_values($values);
        }

        return [
            'tableData' => $list,
            'chartData' => $chartData
        ];
    }

    /**
     * 处理年销售数据
     */
    private function handleYearlySalesData($where, $start_date, $end_date)
    {
        // 查询年销售数据，按年份、产品名称、渠道分组
        $list = Db::name('fzly_sales_report')
            ->whereBetween('date', [$start_date, $end_date])
            ->field(Db::raw("DATE_FORMAT(`date`, '%Y') as year, 
                 product_name, 
                 channel, 
                 SUM(sales_num) as sales_num, 
                 SUM(sales_amount) as sales_amount, 
                 SUM(refund_num) as refund_num, 
                 SUM(refund_amount) as refund_amount, 
                 SUM(actual_sales) as actual_sales")
            )
            ->group("year, product_name, channel")
            ->select();

        // 获取日期范围内的所有年份（格式：年，如2024）
        $years = $this->getYearRange($start_date, $end_date);

        // 初始化图表数据
        $chartData = [
            'categories' => $years,
            'total_sales' => array_fill_keys($years, 0),
            'channel_sales' => [
                'offline' => array_fill_keys($years, 0),
                'ota' => array_fill_keys($years, 0),
                'mini_program' => array_fill_keys($years, 0),
                'official' => array_fill_keys($years, 0)
            ]
        ];

        // 填充图表数据
        foreach ($list as $item) {
            $year = $item['year'];
            if (in_array($year, $years)) {
                $chartData['total_sales'][$year] += $item['actual_sales'];

                if (isset($chartData['channel_sales'][$item['channel']])) {
                    $chartData['channel_sales'][$item['channel']][$year] += $item['actual_sales'];
                }
            }
        }

        // 转换为数组格式
        $chartData['total_sales_array'] = array_values($chartData['total_sales']);
        foreach ($chartData['channel_sales'] as $channel => $values) {
            $chartData['channel_sales_array'][$channel] = array_values($values);
        }

        return [
            'tableData' => $list,
            'chartData' => $chartData
        ];
    }

    /**
     * 获取周范围数组（格式：年-周数）
     */
    private function getWeekRange($start, $end)
    {
        $weeks = [];
        $current = strtotime($start);
        $endTime = strtotime($end);

        while ($current <= $endTime) {
            $week = date('Y-W', $current);
            if (!in_array($week, $weeks)) {
                $weeks[] = $week;
            }
            $current = strtotime('+1 week', $current);
        }
        return $weeks;
    }

    /**
     * 获取月范围数组（格式：年-月）
     */
    private function getMonthRange($start, $end)
    {
        $months = [];
        $current = strtotime($start);
        $endTime = strtotime($end);

        while ($current <= $endTime) {
            $month = date('Y-m', $current);
            if (!in_array($month, $months)) {
                $months[] = $month;
            }
            $current = strtotime('+1 month', $current);
        }
        return $months;
    }

    /**
     * 获取年范围数组（格式：年）
     */
    private function getYearRange($start, $end)
    {
        $years = [];
        $startYear = date('Y', strtotime($start));
        $endYear = date('Y', strtotime($end));

        for ($year = $startYear; $year <= $endYear; $year++) {
            $years[] = (string)$year;
        }
        return $years;
    }
    /**
     * 处理每日客流数据
     */
    private function handleDailyVisitorData($where, $start_date, $end_date)
    {
        $list = Db::name('fzly_visitor_report')
            ->whereBetween('date', [$start_date, $end_date])
            ->field('date, entry_num, exit_num, current_num, staff_entry, onsite_ticket, ota_ticket, official_ticket')
//            ->field('`date`,
//         SUM(entry_num) as total_entry,
//         SUM(exit_num) as total_exit,
//         MAX(current_num) as max_current,
//         SUM(staff_entry) as staff_entry,
//         SUM(onsite_ticket) as onsite_entry,
//         SUM(ota_ticket) as ota_entry,
//         SUM(official_ticket) as official_entry')
//            ->group(Db::raw('`date`)) // 使用Db::raw
            ->select();

        Log::info($list);

        // 2. 获取完整的日期范围（确保无数据的日期也会显示）
        $dates = $this->getDateRange($start_date, $end_date);

        // 3. 构建图表数据（初始化所有日期的指标为0）
        $chartData = [
            'categories' => $dates, // 图表x轴：日期列表
            'total_entry' => array_fill_keys($dates, 0), // 每日总入场数
            'total_exit' => array_fill_keys($dates, 0),  // 每日总出场数
            'max_current' => array_fill_keys($dates, 0)  // 每日最大在场数
        ];

        // 4. 用查询到的数据填充图表数据
        foreach ($list as $item) {
            $date = $item['date'];
            if (in_array($date, $dates)) {
                $chartData['total_entry'][$date] = $item['entry_num'] ?? 0;  // 用 entry_num 替换 total_entry
                $chartData['total_exit'][$date] = $item['exit_num'] ?? 0;    // 用 exit_num 替换 total_exit
                $chartData['max_current'][$date] = $item['current_num'] ?? 0; // 用 current_num 替换 max_current
            }
        }

        // 5. 将图表数据转换为数组（前端图表通常需要数组格式）
        $chartData['total_entry_array'] = array_values($chartData['total_entry']);
        $chartData['total_exit_array'] = array_values($chartData['total_exit']);
        $chartData['max_current_array'] = array_values($chartData['max_current']);

        // 6. 返回表格数据和图表数据
        return [
            'tableData' => $list, // 用于表格展示的原始数据
            'chartData' => [
                'categories' => $dates,
                'entryData' => array_values($chartData['total_entry']),  // 对应前端 entryData
                'exitData' => array_values($chartData['total_exit']),    // 对应前端 exitData
                'currentData' => array_values($chartData['max_current']) // 对应前端 currentData
            ]
        ];
    }
    private function handleWeeklyVisitorData($where, $start_date, $end_date) { return []; }
    private function handleMonthlyVisitorData($where, $start_date, $end_date) { return []; }
    private function handleYearlyVisitorData($where, $start_date, $end_date) { return []; }
    private function handleDailyDeviceData($where, $start_date, $end_date) { return []; }
    private function handleWeeklyDeviceData($where, $start_date, $end_date) { return []; }
    private function handleMonthlyDeviceData($where, $start_date, $end_date) { return []; }
    private function getExportSalesData($type, $start_date, $end_date, $params) { return []; }
    private function getExportStockData($type, $start_date, $end_date, $params) { return []; }
    private function getExportVisitorData($type, $start_date, $end_date, $params) { return []; }
    private function getExportDeviceData($type, $start_date, $end_date, $params) { return []; }
}