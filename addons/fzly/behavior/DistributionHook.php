<?php
namespace addons\fzly\behavior;

use app\common\model\fzly\distribution\Grade;
use app\common\model\fzly\distribution\Log;
use app\common\model\fzly\LessonTg;
use app\common\model\fzly\order\Houseorder;
use app\common\model\fzly\order\Order;
use app\common\model\fzly\distribution\Gralog;
use app\admin\model\User;
use app\common\model\fzly\user\Detail;
use think\Db;
use think\Exception;

class DistributionHook
{
    /**
     * Description: 这是默认的方法，当其他标签找不到时调用此方法
     * @return {[type]}                [description]
     */
    public function run($params)
    {
        /*
         * 分销逻辑
         * 1.根据订单编号查到下单用户 判断用户是否有上级id,如果有 参与分销;如果没有 不参与分销
         * 2.查询用户的上级，直接上级抽取一级分销比例，增加佣金明细;间接上级抽取二级分销比例，增加佣金明细
         * 3.根据付款金额 判断上级代理等级是否需要升级
         */
        \think\Log::info("进入分销");
        if ($params['type']==1){
            $orderInfo = Order::where("order_no",$params['no'])->field("id,order_no,user_id,goods_id,order_amount_total,pay_time")->find();
        }else{
            $orderInfo = Houseorder::where("order_no",$params['no'])->find();

        }
        $userInfo = Detail::where("user_id",$orderInfo['user_id'])->field("id,user_id,parent_id,proxy_level,proxy_amound")->find();
        if (!$orderInfo) {
            return true;
        }
        if (!$userInfo) {
            return true;
        }
        //如果有上级用户，上级用户参与分销
        if ($userInfo['parent_id'] != 0){
            \think\Log::info("有上级,进入分销逻辑");
            //根据上级代理等级抽取佣金
            $grade = Detail::where("user_id",$userInfo->parent_id)->value('proxy_level');
            $info  = $this->commission($orderInfo['order_amount_total'],$grade);
            $this->addCommission($info,$orderInfo,$userInfo,1);
            //判断是否升级
            $this->upgrade($grade,$userInfo['parent_id']);
            //上上级同样抽取佣金及判断升级
            $luser = Detail::get(["user_id"=>$userInfo['parent_id']]);
            if ($luser['parent_id']!=0){
                $grade = Detail::where("user_id",$luser['parent_id'])->value('proxy_level');
                $info  = $this->commission($orderInfo['order_amount_total'],$grade);
                $this->addCommission($info,$orderInfo,$luser,2);
                //判断是否升级
                $this->upgrade($grade,$luser['parent_id']);
            }

        }
    }

    //佣金明细
    public function commission($amount,$grade){
        // $amount=$amount*100;
        $info  = Grade::get($grade);
        return [
            "one_amount"=>bcmul($amount,bcdiv($info['one_level'],100,2),2),
            "two_amount"=>bcmul($amount,bcdiv($info['two_level'],100,2),2),
        ];

    }

    //增加佣金
    public function addCommission($info,$orderInfo,$userInfo,$flag=1){
        Db::startTrans();
        try {
            $proxy_amound = Detail::where("user_id",$userInfo['parent_id'])->value("proxy_amound");
            $pid          = $userInfo['parent_id'];
            if ($flag == 1){
                $amound = bcadd($info['one_amount'],$proxy_amound,2);
            }else{
                $amound = bcadd($info['two_amount'],$proxy_amound,2);
            }

            Detail::update(["proxy_amound"=>$amound],["user_id"=>$pid]);
            Log::update([
//                "pay_amount"=>$orderInfo->order_amount_total,
                "pay_time"  =>$orderInfo->pay_time,
//                "distribution_amount"  =>$amound,
                "status"     =>2,
                "updatetime" =>time(),
            ],[
                "user_id"   =>$pid,
                "order_id"  =>$orderInfo->id,
            ]);
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
        }
    }

    //代理等级升级
    public function upgrade($grade,$id){
        //查询上级代理等级升级条件
        $info  = Grade::get(bcadd($grade,1,2));
        //存在上级等级判断是否符合升级条件，不存在说明是最高级
        if ($info){
            //查询当前用户团队人数
            $people = Detail::where("parent_id",$id)->field("id,user_id")->select()->each(function ($item){
                $user = User::get($item->user_id);
                if ($user){
                    $item->username = $user['username'];
                    $item->createtime = $user['createtime'];
                }else{
                    $item->username = "";
                    $item->createtime = "";
                }
            });
            $people = $people->toArray();
            foreach ($people as $v){
                $children[] = Detail::where("parent_id",$v['id'])->field("id,user_id")->select()->each(function ($item){
                    $user = User::get($item->user_id);
                    if ($user){
                        $item->username = $user['username'];
                        $item->createtime = $user['createtime'];
                    }else{
                        $item->username = "";
                        $item->createtime = "";
                    }
                })->toArray();
            }
            $oneCount = count($people);//一级人数
            $arr = [];
            foreach ($children as $v){
                foreach ($v as $val){
                    $arr[] = $val;
                }
            }
            $peopleCount = bcadd($oneCount,count($arr),2);//总人数
            //查询当前用户下级支付金额
            $ids = implode(',',array_merge(array_column($people,"id"),array_column($arr,"id")));
            $sum = Order::where("user_id","in",$ids)->where("status",2)->sum("order_amount_total");
            //判断是否满足条件
            if ($peopleCount>=$info['team_xx'] && $sum>=$info['amount']){
                Detail::update(["proxy_level"=>$info["id"]],["user_id"=>$id]);
                //升级记录日志
                Gralog::create([
                    "user_id"=>$id,
                    "before"=>$grade,
                    "after"=>$info["id"],
                    "createtime"=>time(),
                    "flag"=>"系统",
                ]);
            }

        }
    }

}