<?php

namespace app\admin\controller\fzly\distribution;

use addons\fzly\library\WxWithdrawal;

use app\common\controller\Backend;
use app\common\model\fzly\user\Detail;

/**
 * 提现记录管理
 *
 * @icon fa fa-circle-o
 */
class Withdraw extends Backend
{

    /**
     * Withdraw模型对象
     * @var \app\common\model\fzly\distribution\Withdraw
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\common\model\fzly\distribution\Withdraw;
        $this->view->assign("statusList", $this->model->getStatusList());
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
                    ->with(['user'])
                    ->where($where)
                    ->order($sort, $order)
                    ->paginate($limit);

            foreach ($list as $row) {
                
                $row->getRelation('user')->visible(['username']);
            }

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }

    /*
     * 提现审核
     */
    public function process($ids){
        if ($this->request->isPost()){
            $row = $this->request->post("row/a");
            $status = $row['status'];
            //如果是微信提现 调用微信提现接口
            $info = \app\common\model\fzly\distribution\Withdraw::get($ids);
            //查询openid
            $detail = Detail::get(["user_id"=>$info['user_id']]);
            if ($status == 3 && $info['type']=='wechat'){

                //生成提现订单号
                $draw_no = "TX".fzly_orderNo();
                $obj = new WxWithdrawal();
                $res = $obj->wx_withdrawal($detail['openid'],$draw_no,$info['withdraw_money']);
                if ((isset($res['batch_status']) && $res['batch_status'] == 'ACCEPTED')){
                    \think\Log::info("提现返回数据:".json_encode($res));
                    \app\common\model\fzly\distribution\Withdraw::update([
                        "status"   => 4,
                        "draw_no"  =>$draw_no,
//                        'batch_id' =>$res['batch_id']
                    ],["id"=>$ids]);
                    //扣除用户余额
                    Detail::update(["freeze_amound"=>0],["user_id"=>$info['user_id']]);


                    return ["code"=>1,"msg"=>"审核成功"];
                }elseif ($res['code'] == "NOT_ENOUGH"){
                    return ["code"=>0,"msg"=>$res['code'].'==>'.$res['message']];
                } else{
                    \app\common\model\fzly\distribution\Withdraw::update([
//                        "status"=>$this->request->post("row.status"),
                        "desc"  =>$row['desc'],
                    ],["id"=>$ids]);

                    return ["code"=>0,"msg"=>$res['code'].'==>'.$res['message']];

                }


            }else{
                \app\common\model\fzly\distribution\Withdraw::update([
                    "status"=>$status,
                    "desc"  =>$row['desc'],
                ],["id"=>$ids]);
                //解冻冻结佣金
                Detail::update(["freeze_amound"=>0,"proxy_amound"=>bcadd($detail['proxy_amound'],$detail['freeze_amound'])],["user_id"=>$info['user_id']]);
            }
            return ["code"=>1,"msg"=>"审核成功"];
        }
        return $this->view->fetch();
    }


    /*
    * 到账
    */
    public function complete($ids){
        //修改订单状态
        $this->model->save(["status"=>4],["id"=>$ids]);
        return ["code"=>1,"msg"=>"修改订单状态成功"];
    }


}
