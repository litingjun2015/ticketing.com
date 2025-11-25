<?php

namespace app\admin\controller\fzly;

use app\common\controller\Backend;
use think\Db;

class Stockvaluation extends Backend
{
    /**
     * StockValuation模型对象
     * @var \app\admin\model\fzly\StockValuation
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\fzly\StockValuation;
        $this->view->assign("valuationStatusList", $this->model->getValuationStatusList());
    }

    /**
     * 查看
     */
    public function index()
    {
        // 假设使用模型查询数据，例如 Stockvaluation 模型
        $model = new \app\admin\model\fzly\Stockvaluation();

        // 构建查询条件（根据实际业务调整）
        $where = [];
        if ($this->request->has('valuation_date')) {
            $where['valuation_date'] = $this->request->get('valuation_date');
        }

        // 设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            // Ajax请求逻辑保持不变
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
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

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            // 分页查询（每页10条数据，paginate() 方法返回分页对象）
            $list = $model->where($where)->paginate(10);

            // 将分页数据和分页对象传递给模板
            $this->assign('list', $list); // 列表数据（包含分页信息）
            $this->assign('page', $list->render()); // 分页HTML（也可直接传递分页对象）

            return json($result);
        } else {
            // 非Ajax请求：查询数据并传递给模板
            list($where, $sort, $order) = $this->buildparams(); // 构建查询条件（不含分页）
            $list = $this->model
                ->where($where)
                ->order($sort, $order)
                ->select(); // 查询数据（可根据需求添加分页）
            $this->view->assign('list', $list); // 向模板传递$list变量
        }
        return $this->view->fetch();
    }

    /**
     * 生成价值评估
     */
    public function evaluate()
    {
        if ($this->request->isPost()) {
            // 生成评估逻辑实现
            $this->success(__('Evaluate success'));
        }
        return $this->view->fetch();
    }

    /**
     * 导出评估报表
     */
    public function export()
    {
        // 导出逻辑实现
        $this->success(__('Export success'));
    }
}