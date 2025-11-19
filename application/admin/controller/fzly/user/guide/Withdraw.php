<?php

namespace app\admin\controller\fzly\user\guide;

use addons\fzly\library\WxWithdrawal;
use app\common\controller\Backend;
use app\common\model\fzly\user\Detail;
use app\common\model\User;

/**
 * 提现记录管理
 *
 * @icon fa fa-circle-o
 */
class Withdraw extends Backend
{

    /**
     * Withdraw模型对象
     * @var \app\common\model\fzly\user\guide\Withdraw
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\common\model\fzly\user\guide\Withdraw;
        $this->view->assign("typeList", $this->model->getTypeList());
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
                if ($row->type) {
                    $row->type = json_decode($row->type,true);
                    $row->type = $row->type['type'];
                    if ($row->type == 1){
                        $row->type = "银行卡";
                    }elseif ($row->type == 2){
                        $row->type = "微信";
                    }else{
                        $row->type = "支付宝";
                    }
                }
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
            $status = $this->request->post("row.status");
            //如果是微信提现 调用微信提现接口
            $info = \app\common\model\fzly\user\guide\Withdraw::get($ids);
            if ($info['type']) {
                $info['type'] = json_decode($info['type'],true)['type'];

            }
            if ($status == 3 && $info['type']==2){
                //查询openid
                $detail = Detail::get(["user_id"=>$info['user_id']]);
                //生成提现订单号
                $draw_no = "TX".fzly_orderNo();
                $obj = new WxWithdrawal();
                $res = $obj->wx_withdrawal($detail['openid'],$draw_no,$info['withdraw_money']);
//                var_dump($res);die;
                if ((isset($res['batch_status']) && $res['batch_status'] == 'ACCEPTED')){
                    \think\Log::info("提现返回数据:".json_encode($res));
                    \app\common\model\fzly\user\guide\Withdraw::update([
                        "status"   => 4,
                        "draw_no"  =>$draw_no,
                    ],["id"=>$ids]);
                    //微信提现成功后 扣除用户余额
                    User::where("id",$info['user_id'])->setDec("money",$info['withdraw_money']);
                    return ["code"=>1,"msg"=>"审核成功"];
                }elseif ($res['code'] == "NOT_ENOUGH"){
                    return ["code"=>0,"msg"=>$res['code'].'==>'.$res['message']];
                } else{
                    \app\common\model\fzly\user\guide\Withdraw::update([
                        "status"=>2,
                        "desc"  =>$this->request->post("row.desc"),
                    ],["id"=>$ids]);
                    return ["code"=>0,"msg"=>$res['code'].'==>'.$res['message']];
                }

            }elseif ($status == 2){
                \app\common\model\fzly\user\guide\Withdraw::update([
                    "status"=>2,
                    "desc"  =>$this->request->post("row.desc"),
                ],["id"=>$ids]);
            } else{
                \app\common\model\fzly\user\guide\Withdraw::update([
                    "status"=>4,
                ],["id"=>$ids]);
            }
            return ["code"=>1,"msg"=>"审核成功"];
        }
        return $this->view->fetch();
    }

}
