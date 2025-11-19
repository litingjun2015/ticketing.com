<?php
namespace addons\fzly\job;
use app\common\model\fzly\coupon\Coupon;
use app\common\model\fzly\coupon\Receive;
use app\common\model\fzly\house\OrderCalendar;
use app\common\model\fzly\order\Detail;
use app\common\model\fzly\order\Houseorder;
use app\common\model\fzly\order\Order;
use think\Db;
use think\Log;
use think\queue\Job;

/**
 * 用于处理超时订单自动取消
 */
class Overtime
{
    /**
     * fire是消息队列默认调用的方法
     * @param Job $job 当前的任务对象
     * @param array|mixed $data 发布任务时自定义的数据
     */
    public function fire(Job $job, $data)
    {
        Log::log("进入订单超时取消队列");
        //执行业务处理
        if($this->doJob($data)){
            $job->delete();//任务执行成功后删除
            Log::log("dismiss job has been down and deleted");
        }else{
            //检查任务重试次数
            if($job->attempts() > 3){
                Log::log("dismiss job has been retried more that 3 times");
                $job->delete();
            }else{
                $job->release(2);
            }
        }
    }


    /**
     * 根据消息中的数据进行实际的业务处理
     */
    private function doJob($data)
    {
        // 实际业务流程处理
        if ($data['type']==1){
            //查询该订单是否已付款
            $orderInfo = Order::get(['order_no'=>$data['orderNo']]);
            if (!$orderInfo){
                return true;
            }
            if ($orderInfo->status != 1){
                //订单已付款
                Log::log("该订单已支付".$data['orderNo']);
                return true;
            }else{
                Log::log("该订单未支付".$data['orderNo']);
                Db::startTrans();
                try {
                    //订单已超时 更新订单状态
                    Order::where(['id'=>$orderInfo['id']])->update(['status'=>5]);
                    Detail::where(['order_id'=>$orderInfo['id']])->update(['status'=>3]);
                    //判断是否使用优惠券
                    if ($orderInfo->coupon_id > 0){
                        //更新优惠券为未使用，判断优惠券是否过期
                        $coupon = Coupon::get($orderInfo->coupon_id);
                        if (time() > $coupon->end_time){
                            $res5 = Receive::where(["user_id"=>$orderInfo['user_id'],"coupon_id"=>$orderInfo->coupon_id])->update(['state'=>-1]);
                        }else{
                            $res5 = Receive::where(["user_id"=>$orderInfo['user_id'],"coupon_id"=>$orderInfo->coupon_id])->update(['state'=>0]);
                        }
                        Log::log("优惠券更新状态".$res5);
                    }
                    // 提交事务
                    Db::commit();
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    Log::log($e->getMessage());
                    return true;
                }
                return true;
            }
        }else{
            //查询该订单是否已付款
            $orderInfo = Houseorder::get(['order_no'=>$data['orderNo']]);
            if (!$orderInfo){
                return true;
            }
            if ($orderInfo->status != 1){
                //订单已付款
                Log::log("该订单已支付".$data['orderNo']);
                return true;
            }else{
                Log::log("该订单未支付".$data['orderNo']);
                Db::startTrans();
                try {
                    //订单已超时 更新订单状态
                    Houseorder::where(['id'=>$orderInfo['id']])->update(['status'=>6]);
                    OrderCalendar::where(['orderid'=>$orderInfo['order_no']])->update(['status'=>'canceled']);

                    //判断是否使用优惠券
                    if ($orderInfo->coupon_id > 0){
                        //更新优惠券为未使用，判断优惠券是否过期
                        $coupon = Coupon::get($orderInfo->coupon_id);
                        if (time() > $coupon->end_time){
                            $res5 = Receive::where(["user_id"=>$orderInfo['user_id'],"coupon_id"=>$orderInfo->coupon_id])->update(['state'=>-1]);
                        }else{
                            $res5 = Receive::where(["user_id"=>$orderInfo['user_id'],"coupon_id"=>$orderInfo->coupon_id])->update(['state'=>0]);
                        }
                        Log::log("优惠券更新状态".$res5);
                    }
                    // 提交事务
                    Db::commit();
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    Log::log($e->getMessage());
                    return true;
                }
                return true;
            }
        }

    }
}