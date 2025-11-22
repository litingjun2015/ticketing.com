<?php

namespace app\admin\controller\fzly;

use app\common\controller\Backend;

/**
 * 库存变动记录
 *
 * @icon fa fa-history
 */
class StockLog extends Backend
{
    /**
     * StockLog模型对象
     * @var \app\common\model\fzly\StockLog
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\common\model\fzly\StockLog;
        $this->view->assign("channelList", $this->model->getChannelList());
        $this->view->assign("changeTypeList", $this->model->getChangeTypeList());
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

            return json($result);
        }
        return $this->view->fetch();
    }
}