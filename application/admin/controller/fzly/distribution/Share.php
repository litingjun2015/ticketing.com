<?php

namespace app\admin\controller\fzly\distribution;

use app\admin\model\User;
use app\common\controller\Backend;

/**
 * 分享邀请管理
 *
 * @icon fa fa-share
 */
class Share extends Backend
{

    /**
     * Share模型对象
     * @var \app\common\model\fzly\distribution\Share
     */
    protected $model = null;
    protected $searchFields = 'id,name,pname';
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\common\model\fzly\distribution\Share;

    }



    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */


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

            $list = $this->model
                    ->where($where)
                    ->order($sort, $order)
                    ->paginate($limit);

            foreach ($list as $row) {
                $user1 = User::where("id",$row->user_id)->find();
                if ($user1){
                    $row->name = $user1['username'];
                }else{
                    $row->name = "";
                }
                $user2 = User::where("id",$row->p_id)->find();
                if ($user2){
                    $row->pname = $user2['username'];
                }else{
                    $row->pname = "";
                }
            }

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }

}
