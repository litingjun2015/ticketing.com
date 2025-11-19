<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace addons\fzly\library\GatewayWorker\Applications\Fzly;
/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */

use addons\fzly\library\Common;
use app\admin\model\User;
use \GatewayWorker\Lib\Gateway;

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events
{
    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     * 
     * @param int $client_id 连接id
     */
    public static function onWebSocketConnect($client_id,$data)
    {
        // 安全检查
        array_walk_recursive($data, ['addons\fzly\library\Common', 'checkVariable']);
        //验证身份
        if (isset($data['get']['token'])) {
            $agreement                    = 'https://';
            $_SESSION['cdn_url']          = $agreement . $data['server']['SERVER_NAME']; //设置服务器域名
            // 验证FA用户身份
            $user_id = Common::checkFaUser($data['get']['token']);
//
            if ($user_id) {
                $userInfo = User::field("id,username,mobile,avatar")->where(['id'=>$user_id])->find();
                $userInfo['avatar'] = cdnurl($userInfo['avatar'],true);
                Gateway::bindUid($client_id, $user_id);

            }else{
                Gateway::sendToClient($client_id, json_encode([
                    'code'    => 0,
                    'msgtype' => 'clear',
                    'msg'     => '用户信息不正确',
                ]));
                return;
            }

        }else{
            Gateway::sendToClient($client_id, json_encode([
                'code'    => 0,
                'msgtype' => 'clear',
                'msg'     => '缺少token',
            ]));
            return;
        }
        // 向当前client_id发送数据
        Gateway::sendToClient($client_id, json_encode(['msgtype' => 'initialize',"data" => $userInfo]));
    }
    
   /**
    * 当客户端发来消息时触发
    * @param int $client_id 连接id
    * @param mixed $message 具体消息
    */
   public static function onMessage($client_id, $message)
   {
       // 分发到控制器
       $data = json_decode($message, true);

       // 安全检查
       array_walk_recursive($data, ['addons\fzly\library\Common', 'checkVariable']);

       if (!is_array($data) || !isset($data['c']) || !isset($data['a'])) {
           Common::showMsg($client_id, '错误的请求！');
           return;
       }

       $filename = __DIR__ . '/controller/' . $data['c'] . '.php'; //载入文件类似/controller/index.php

       if (file_exists($filename)) {
           require_once $filename;
           if (!class_exists($data['c'], false)) {
               common::showMsg($client_id,  '您访问的控制器不存在！');
               return;
           }
       } else {
           common::showMsg($client_id, '您访问的文件不存在！');
           return;
       }
       $o = new $data['c'](); // 新建对象

       if (!method_exists($o, $data['a'])) {
           common::showMsg($client_id, '您访问的方法不存在！');
           return;
       }
       $data['data'] = isset($data['data']) ? $data['data'] : '';

       call_user_func_array([$o, $data['a']], [$client_id, $data['data']]); //调用对象$o($c)里的方法$a
   }
   
   /**
    * 当用户断开连接时触发
    * @param int $client_id 连接id
    */
   public static function onClose($client_id)
   {
       // 向所有人发送 
       GateWay::sendToAll("$client_id logout\r\n");
   }
}
