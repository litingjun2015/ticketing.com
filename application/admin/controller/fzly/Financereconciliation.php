<?php

namespace app\admin\controller\fzly;

use app\common\controller\Backend;
use think\Db;

class Financereconciliation extends Backend
{
    /**
     * FinanceReconciliation模型对象
     * @var \app\admin\model\fzly\FinanceReconciliation
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\fzly\FinanceReconciliation;
        $this->view->assign("reconciliationStatusList", $this->model->getReconciliationStatusList());
    }

    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->with(['profitDetails'])
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->with(['profitDetails'])
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 查看详情
     */
    public function detail($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $this->view->assign("row", $row);

        // 获取利润明细
        $profitDetails = Db::name('fzly_profit_detail')
            ->where('reconciliation_id', $ids)
            ->select();
        $this->view->assign("profitDetails", $profitDetails);

        return $this->view->fetch();
    }

    /**
     * 生成对账报表
     */
    public function generate()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                // 生成对账逻辑实现
                // 1. 验证日期范围
                // 2. 计算销售总额、成本总额、利润总额
                // 3. 创建对账记录
                // 4. 创建利润明细记录

                $this->success(__('Generate success'));
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }

    /**
     * 导出对账报表
     */
    public function export($ids = null)
    {
        if ($ids) {
            // 导出逻辑实现
            $this->success(__('Export success'));
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }
}