<?php
namespace addons\fzly\job;

use app\common\model\fzly\user\Detail;
use think\Log;
use think\queue\Job;

/**
 * 用于处理消息推送
 */
class SendMessage
{
    /**
     * fire是消息队列默认调用的方法
     * @param Job $job 当前的任务对象
     * @param array|mixed $data 发布任务时自定义的数据
     */
    public function fire(Job $job, $data)
    {
        Log::log("进入消息推送队列");
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
        $sendObj = new \addons\fzly\library\SendMessage();
        if (!isset($data['openid'])){
            return true;
        }
        $result = $sendObj->send_temp($data['openid'],$data['masterplate'],$data['data']);
        if ($data['point'] == 1){
            $point = "支付成功";
        }elseif ($data['point'] == 2){
            $point = "核销成功";
        }else{
            $point = "未知";
        }
        Log::log("消息推送返回结果：".json_encode($result));
        if (isset($result['errcode']) && $result['errcode'] == 0){
            //添加推送日志
            \app\common\model\fzly\message\Log::create([
                "user_id"=>Detail::where("openid",$data['openid'])->value("user_id"),
                "point"=>$point,
                "status"=>"1",
                "result"=>json_encode($result),
                "createtime"=>time()
            ]);
            return true;
        }else{
            //添加推送日志
            \app\common\model\fzly\message\Log::create([
                "user_id"=>Detail::where("openid",$data['openid'])->value("user_id"),
                "point"=>$point,
                "status"=>"0",
                "result"=>json_encode($result),
                "createtime"=>time()
            ]);
            return false;
        }

    }
}