<?php

namespace app\admin\controller\fzly;

use app\admin\model\fzly\Stock as StockModel;
use app\common\controller\Backend;
use think\Db;
use fast\Date;

/**
 * 库存管理
 *
 * @icon fa fa-cubes
 */
class Stock extends Backend
{
    /**
     * Stock模型对象
     * @var \app\common\model\fzly\Stock
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new StockModel();
        $this->view->assign("channelList", $this->model->getChannelList());
        $this->view->assign("statusList", $this->model->getStatusList());
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
                ->with(['product'])
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->with(['product'])
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            foreach ($list as $row) {
                $row->getRelation('product')->visible(['name', 'type']);
            }

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 获取库存预警值
     */
    public function getWarnValue()
    {
        $product_id = $this->request->get('product_id');
        $channel = $this->request->get('channel');

        $warn = Db::name('fzly_stock_warn')
            ->where('product_id', $product_id)
            ->where('channel', $channel)
            ->find();

        if ($warn) {
            return json(['code' => 0, 'data' => ['warn_value' => $warn['warn_value']]]);
        }
        return json(['code' => 0, 'data' => ['warn_value' => 0]]);
    }
 
    /**
     * 库存报表
     */
    public function report()
    {
        $type = $this->request->param('type', 'daily');
        $start_date = $this->request->param('start_date', date('Y-m-d', strtotime('-7 days')));
        $end_date = $this->request->param('end_date', date('Y-m-d'));
        $channel = $this->request->param('channel', 'all');

        $this->view->assign([
            'type' => $type,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'channel' => $channel,
            'channelList' => $this->model->getChannelList()
        ]);

        return $this->view->fetch();
    }

    /**
     * 导出报表
     */
    public function export()
    {
        // 导出逻辑实现
        $type = $this->request->param('type', 'excel');
        // ...
    }

    /**
     * 库存可视化
     */
    public function visual()
    {
        $visualList = Db::name('fzly_stock_visual')->where('status', 1)->order('weigh', 'asc')->select();
        $this->view->assign('visualList', $visualList);
        return $this->view->fetch();
    }
}