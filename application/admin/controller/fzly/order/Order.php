<?php

namespace app\admin\controller\fzly\order;

use addons\epay\library\Service;
use app\admin\model\User;
use app\common\controller\Backend;
use app\common\model\fzly\admission\Admission;
use app\common\model\fzly\coupon\Coupon;
use app\common\model\fzly\coupon\Receive;
use app\common\model\fzly\distribution\Log as Dislog;
use app\common\model\fzly\order\Detail;
use app\common\model\fzly\user\guide\Product;
use app\common\model\fzly\user\Money;
use app\common\model\fzly\user\Travel;
use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\exception\PDOException;
use think\exception\ValidateException;
use think\Log;
use Yansongda\Pay\Pay;

/**
 * 订单管理
 *
 * @icon fa fa-circle-o
 */
class Order extends Backend
{

    /**
     * Order模型对象
     * @var \app\common\model\fzly\order\Order
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\common\model\fzly\order\Order;
        $this->view->assign("orderTypeList", $this->model->getOrderTypeList());
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
//            var_dump($this->model->getLastSql());die;

            foreach ($list as $row) {
                
                $row->getRelation('user')->visible(['username']);
                $row->guide = User::get($row->guide_id);
                if ($row->guide){
                    $row->guide_name = $row->guide->username;
                }else{
                    $row->guide_name = "";
                }
//                if ($row->order_type == 1){
//                    $row->goods_id = Admission::get($row->goods_id)->goods_id;
//                }else{
//                    $row->goods_id = Product::get($row->goods_id)->id;
//                }
            }

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 详情
     */
    public function detail($ids)
    {
        $data = $this->model->where("id",$ids)->find();
        if (false === $this->request->isPost()) {
            //查询该订单的详情
            $detail = Detail::where(["order_id"=>$ids])->select()
                    ->each(function ($item){
                        $travel = Travel::get($item['travel_id']);
                        $item->name = $travel['name'];
                        $item->tel = $travel['tel'];
                        if ($item['status']==1){
                            $item->status = "待核销";
                        }elseif ($item['status']==2){
                            $item->status = "已核销";
                        }elseif ($item['status']==3){
                            $item->status = "已取消";
                        }
                    });
            if ($data['order_type']==1){
                $data['order_type'] = "门票订单";
                $goods = Admission::get($data['goods_id']);
            }else{
                $data['order_type'] = "导游订单";
                $goods = Product::get($data['goods_id']);
            }
            //产品名称
            $data['goods_name'] = $goods['title'];
            $this->view->assign("row", $data);
            $this->view->assign("detail", $detail);
            return $this->view->fetch();
        }
        $params = $this->request->post('row/a');

        \app\common\model\fzly\order\Order::where(["id"=>$ids[0]])->update(["status"=>$params['status']]);
        $this->success();
    }

    /**
     * 退款
     */
    public function refund($ids)
    {
        if (false === $this->request->isPost()) {
            $data = $this->model->where("id",$ids)->find();

            $this->view->assign("data", $data);
            return $this->view->fetch();
        }
        $params = $this->request->post('row/a');
        $res = \app\common\model\fzly\order\Order::get($ids);
        $order = $res;

        if ($params['status']==1){
            //审核通过
            //原路返回
            Db::startTrans();
            try {
                $orderd['transaction_id'] = $res['out_trade_no'];
                $orderd['out_refund_no'] = date('YmdHis') . substr(bcadd($ids, strrev(date('YmdHis')), 0), -8) . bcadd($ids, time(), 0);
                $orderd['total_fee'] = bcmul($res['order_amount_total'], 100, 0); // 原支付交易的订单总金额
                $orderd['refund_fee'] = bcmul($res['order_amount_total'], 100, 0); // 退款金额
                $orderd['notify_url'] = request()->domain()."/addons/fzly/order/refund_notifyx";
                $config = Service::getConfig();
                $pay = Pay::wechat($config);
                $result = $pay->refund($orderd);
                Log::info("退款结果：".json_encode($result));
                if ($result['return_code']=="SUCCESS"){
                    if ($order->coupon_id > 0){
                        //更新优惠券为未使用，判断优惠券是否过期
                        $coupon = Coupon::get($order->coupon_id);
                        if (time() > $coupon->end_time){
                            $res5 = Receive::update(['state'=>-1],["user_id"=>$order['user_id'],"coupon_id"=>$order->coupon_id]);
                        }else{
                            $res5 = Receive::update(['state'=>0],["user_id"=>$order['user_id'],"coupon_id"=>$order->coupon_id]);
                        }
                        Log::log("优惠券更新状态".$res5);
                    }
                    //如果有分销
                    $distribution = Dislog::where(['order_id' => $order['id']])->select();
                    if ($distribution){
                        foreach ($distribution as $k => $v){
                            //减少佣金
                            $userDetail = \app\common\model\fzly\user\Detail::where(['user_id' => $v['user_id']])->find();
                            $userDetail->proxy_amound = bcsub($userDetail->proxy_amound,$v['distribution_amount'],2);
                            $userDetail->save();
                            //删除分销记录
                            Dislog::destroy($v['id']);

                        }
                    }
                    $r = \app\common\model\fzly\order\Order::update(['status' => 5,'out_refund_no'=>$order['out_refund_no'],'refund_id'=>$result['refund_id'],"updatetime"=>time()], ['id' => $order['id']]);

                }else{
                    return json(["code"=>0,"msg"=>"退款失败,请稍后重试"]);
                }
                Db::commit();
            }catch (\Exception $e){
                // 回滚事务
                Db::rollback();
                $this->error("退款失败：".$e->getMessage().",行号：".$e->getLine().",文件：".$e->getFile());
            }
        }else{
            //审核拒绝 订单回滚到上一状态
            \app\common\model\fzly\order\Order::where(["id"=>$ids])->update(["status"=>$res['last_status']]);
        }
        return json(["code"=>1,"msg"=>"审核成功"]);
    }


    /**
     * 编辑
     *
     * @param $ids
     * @return string
     * @throws DbException
     * @throws \think\Exception
     */
    public function edit($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds) && !in_array($row[$this->dataLimitField], $adminIds)) {
            $this->error(__('You have no permission'));
        }
        if (false === $this->request->isPost()) {
            $this->view->assign('row', $row);
            return $this->view->fetch();
        }
        $params = $this->request->post('row/a');
        if (empty($params)) {
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $params = $this->preExcludeFields($params);
        if ($params['status']==3){
            Detail::where(["order_id"=>$ids])->update(["status"=>2]);
        }
        if ($params['status']<$row['status']){
            $this->error("订单状态不能降级");
        }
        $result = false;
        Db::startTrans();
        try {
            //是否采用模型验证
            if ($this->modelValidate) {
                $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                $row->validateFailException()->validate($validate);
            }

            if ($params['status'] == 3){
                $params['is_hx'] = 1;
            }
            $result = $row->allowField(true)->save($params);
            //增加导游收益
            if ($row['order_type'] == 2 && $row['status'] == 3 && $row['is_hx']==0) {
                $guide = \app\admin\model\User::get($row['guide_id']);
                $guide_money = bcadd($guide['money'], $row['order_amount_total'], 2);
                Money::create([
                    "gudie_id"=>$row['guide_id'],
                    "user_id"=>$row['user_id'],
                    "money"=>$row['order_amount_total'],
                    "before"=>$guide['money'],
                    "after"=>$guide_money,
                    "y"=>date("Y"),
                    "m"=>date("m"),
                    "d"=>date("d"),
                    "createtime"=>time(),
                ]);
                $guide->money = $guide_money;
                $guide->save();
            }

            Db::commit();
        } catch (ValidateException|PDOException|\Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        if (false === $result) {
            $this->error(__('No rows were updated'));
        }
        $this->success();
    }

    /**
     * 删除
     *
     * @param $ids
     * @return void
     * @throws DbException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     */
    public function del($ids = null)
    {
        if (false === $this->request->isPost()) {
            $this->error(__("Invalid parameters"));
        }
        $ids = $ids ?: $this->request->post("ids");
        if (empty($ids)) {
            $this->error(__('Parameter %s can not be empty', 'ids'));
        }
        $pk = $this->model->getPk();
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            $this->model->where($this->dataLimitField, 'in', $adminIds);
        }
        $list = $this->model->where($pk, 'in', $ids)->select();

        $count = 0;
        Db::startTrans();
        try {
            foreach ($list as $item) {
                $count += $item->delete();
                Detail::where(["order_id"=>$item['id']])->delete();
            }
            Db::commit();
        } catch (PDOException|\Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        if ($count) {
            $this->success();
        }
        $this->error(__('No rows were deleted'));
    }


}
