<?php

namespace app\admin\controller\fzly;

use app\common\controller\Backend;
use think\Db;
use think\Exception;

/**
 * 数据同步管理
 *
 * @icon fa fa-refresh
 */
class DataSync extends Backend
{
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = Db::name('fzly_data_sync_log');
        $this->view->assign("moduleList", [
            'ticket' => __('Ticketing data'),
            'checkin' => __('Check-in data'),
            'inventory' => __('Inventory data'),
            'finance' => __('Financial data'),
            'device' => __('Device data')
        ]);
        $this->view->assign("statusList", [
            'success' => __('Success'),
            'fail' => __('Fail')
        ]);
    }

    /**
     * 查看
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
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
     * 手动同步数据
     */
    public function sync()
    {
        $module = $this->request->param('module');
        if (!$module) {
            return json(['code' => 1, 'msg' => __('Please select module')]);
        }

        $startTime = microtime(true);
        $status = 'success';
        $message = '';
        $count = 0;

        try {
            switch ($module) {
                case 'ticket':
                    $count = $this->syncTicketData();
                    $message = __('Ticketing data sync completed');
                    break;
                case 'checkin':
                    $count = $this->syncCheckinData();
                    $message = __('Check-in data sync completed');
                    break;
                case 'inventory':
                    $count = $this->syncInventoryData();
                    $message = __('Inventory data sync completed');
                    break;
                case 'finance':
                    $count = $this->syncFinanceData();
                    $message = __('Financial data sync completed');
                    break;
                case 'device':
                    $count = $this->syncDeviceData();
                    $message = __('Device data sync completed');
                    break;
                default:
                    return json(['code' => 1, 'msg' => __('Invalid module')]);
            }
        } catch (Exception $e) {
            $status = 'fail';
            $message = $e->getMessage();
        }

        $endTime = microtime(true);
        $duration = round(($endTime - $startTime) * 1000);

        // 记录同步日志
        Db::name('fzly_data_sync_log')->insert([
            'module' => $module,
            'sync_time' => time(),
            'data_count' => $count,
            'status' => $status,
            'message' => $message,
            'duration' => $duration
        ]);

        return json([
            'code' => $status == 'success' ? 0 : 1,
            'msg' => $message,
            'data' => [
                'count' => $count,
                'duration' => $duration
            ]
        ]);
    }

    // 各模块同步方法
    private function syncTicketData()
    {
        // 实现售票数据同步逻辑
        return 100; // 示例：同步了100条数据
    }

    private function syncCheckinData()
    {
        // 实现检票数据同步逻辑
        return 80; // 示例：同步了80条数据
    }

    private function syncInventoryData()
    {
        // 实现库存数据同步逻辑
        return 50; // 示例：同步了50条数据
    }

    private function syncFinanceData()
    {
        // 实现财务数据同步逻辑
        return 30; // 示例：同步了30条数据
    }

    private function syncDeviceData()
    {
        // 实现设备数据同步逻辑
        return 20; // 示例：同步了20条数据
    }
}