<?php
namespace app\admin\controller\fzly;

use app\admin\model\fzly\Stock as StockModel;
use app\admin\model\fzly\StockAdjustLog;
use app\admin\model\fzly\StockWarnLog;
use app\common\controller\Backend;
use think\Db;
use think\Exception;
use think\Log;

class Stock_old extends Backend
{
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new StockModel();
    }

    // 库存列表
    public function index()
    {
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $total = $this->model
                ->where($where)
                ->count();

            $list = $this->model
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            // 检查库存预警状态
            $this->model->checkWarnStatus();

            $result = [
                "total" => $total,
                "rows" => $list
            ];

//            return json($result);

            // 修正后的代码
            return json([
                'code' => 0,               // 成功状态码
                'msg' => '获取成功',
                'count' => $total,         // 总条数（对应 layui 的 count）
                'data' => $list            // 数据列表（对应 layui 的 data）
            ]);
        }

        // 获取渠道列表
        $channels = ['官网', 'OTA', '窗口'];
        $this->assign('channels', $channels);

        return $this->view->fetch();
    }

    // 调整库存
    public function adjust()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            // 优化后
            $missing = [];
            if (empty($data['id'])) $missing[] = '库存ID(id)';
            if (!isset($data['adjust_type'])) $missing[] = '调整类型(adjust_type)';
            if (empty($data['adjust_value'])) $missing[] = '调整数量(adjust_value)';
            if (!empty($missing)) {
                $this->error('参数不完整，缺失：' . implode('、', $missing));
            }

            // 新增：验证adjust_type必须为1或2
            if (!in_array($data['adjust_type'], [1, 2])) {
                $this->error('调整类型无效，请选择增加或减少');
            }

            $data['operator_id'] = $this->auth->id;
            $data['operator_name'] = $this->auth->nickname;

            $stockModel = new StockModel();
            $result = $stockModel->adjustStock($data);

            if ($result) {
                $this->success('库存调整成功');
            } else {
                $this->error('库存调整失败');
            }
        }

        $id = $this->request->get('id');
        $stock = $this->model->find($id);
        if (!$stock) {
            $this->error('库存记录不存在');
        }

        $this->assign('stock', $stock);
        return $this->view->fetch();
    }

    // 库存调整日志
    public function adjustLog()
    {
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $model = new StockAdjustLog();

            $total = $model
                ->where($where)
                ->count();

            $list = $model
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $result = [
                "total" => $total,
                "rows" => $list
            ];

            return json($result);
        }

        return $this->view->fetch();
    }

    // 库存预警记录
    public function warnLog()
    {
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $model = new StockWarnLog();

            $total = $model
                ->where($where)
                ->count();

            $list = $model
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $result = [
                "total" => $total,
                "rows" => $list
            ];

            return json($result);
        }

        return $this->view->fetch();
    }

    // 处理预警
    public function handleWarn()
    {
        $id = $this->request->post('id');
        if (!$id) {
            $this->error('参数错误');
        }

        $model = new StockWarnLog();
        $result = $model->handleWarn($id, $this->auth->id, $this->auth->nickname);

        if ($result) {
            $this->success('处理成功');
        } else {
            $this->error('处理失败');
        }
    }

    // 同步库存预警状态
    public function syncWarnStatus()
    {
        try {
            $this->model->checkWarnStatus();
            $this->success('同步成功');
        } catch (Exception $e) {
            $this->error('同步失败：' . $e->getMessage());
        }
    }
}