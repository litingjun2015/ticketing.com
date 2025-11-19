<?php

use app\admin\model\User;
use app\common\model\fzly\Record;
use app\common\model\fzly\Session;
use GatewayWorker\Lib\Gateway;
use addons\fzly\library\Common;
use think\Db;


class Message{

    public function __construct()
    {

    }


    /*用户端心跳*/
    public function ping()
    {

    }

    /*
     * 获取会话列表
     */
    public function sessionList($client_id, $data)
    {
        if (!isset($data['user_id']) || empty($data['user_id'])){
            common::showMsg($client_id, '缺少user_id！');
            return '';
        }
        $user_id = $data['user_id'];
        $limit   = isset($data['limit'])?$data['limit']:40;
        [$total,$session] = Common::sessionList($user_id, $limit);
        Gateway::sendToClient($client_id, json_encode([
            'msgtype' => 'session_list',
            'data'    => $session,
            'total'    => $total,
            'code'    => 1
        ]));
    }

    /*
     * 获取会话id
     */
    public function getSessionId($client_id,$data){
        if (!isset($data['user_id']) || empty($data['user_id'])){
            common::showMsg($client_id, '缺少user_id！');
            return '';
        }
        if (!isset($data['csr_id']) || empty($data['csr_id'])){
            common::showMsg($client_id, '缺少csr_id！');
            return '';
        }
        $ret = Session::where('user_id', $data['user_id'])->where('csr_id', $data['csr_id'])->find();
        if ($ret){
            if ($ret['is_del_user'] == 1){
                Session::where('user_id', $data['user_id'])->where('csr_id', $data['csr_id'])->update(['is_del_user'=>0]);
            }
            $session_info = $ret;
        }else{
            $ret2 = Session::where('csr_id', $data['user_id'])->where('user_id', $data['user_id'])->find();
            if ($ret2){
                if ($ret2['is_del_user'] == 1){
                    Session::where('csr_id', $data['user_id'])->where('user_id', $data['user_id'])->update(['is_del_csr'=>0]);
                }
                $session_info = $ret2;
            }else{
                Session::create([
                    "user_id"    =>$data['user_id'],
                    "csr_id"     =>$data['csr_id'],
                    "createtime" =>time(),
                ]);
                $session_info = Session::where('user_id', $data['user_id'])->where('csr_id', $data['csr_id'])->find();
            }
        }
        if (!isset($session_info['id'])){
            Gateway::sendToClient($client_id, json_encode([
                'msgtype' => 'getSessionId',
                'code'    => 0,
                'data'    => [
                    'session_id' => "",
                ],
            ]));
        }
        Gateway::sendToClient($client_id, json_encode([
            'msgtype' => 'getSessionId',
            'code'    => 1,
            'data'    => [
                'session_id' => $session_info['id'],
            ],
        ]));
    }

    /*
     * 发送消息
     */
    public function sendMessage($client_id, $data)
    {

        if (!isset($data['session_id'])) {
            common::showMsg($client_id, '发送失败,会话找不到啦！');
            return '';
        }

        if (!isset($data['message']) || $data['message'] == '') {
            common::showMsg($client_id, '请输入消息内容！');
            return '';
        }

        if (!isset($data['message_type'])) {
            common::showMsg($client_id, '消息类型错误！');
            return '';
        }

        if (!isset($data['user_id'])) {
            common::showMsg($client_id, '缺少用户id！');
            return '';
        }

        $res = Common::chatRecord($data);

        Gateway::sendToClient($client_id, json_encode($res));
    }

    /*
     * 加载更多聊天记录
     */
    public function chatRecord($client_id, $data)
    {
        $page_count = 20; //一次加载20条
        if (!isset($data['page'])) {
            $data['page'] = 1;
        }

        if (!isset($data['session_id'])) {
            common::showMsg($client_id, '请输入session_id！');
            return '';
        }
        if (!isset($data['user_id'])) {
            common::showMsg($client_id, '请输入user_id！');
            return '';
        }

        // 会话信息 查询用户 判断是否已经删除
        $session_info = Db::name('fzly_session')->where('id', (int)$data['session_id'])->find();
        if (!$session_info){
            Gateway::sendToClient($client_id, json_encode([
                'msgtype' => 'chat_record',
                'code'    => 0,
                'data'    => [
                    'chat_record'  => [],
                    'session_info' => [
                        'nickname' => '无此会话',
                    ],
                    'next_page'    => 'done',
                    'page'         => $data['page'],
                ],
            ]));
            return '';
        }

        if ($session_info['user_id'] == $data['user_id']){
            $chat_record_count = Db::name('fzly_record')->where('session_id', $data['session_id'])->where('is_del_sender',0)->count('id');
        }else{
            $chat_record_count = Db::name('fzly_record')->where('session_id', $data['session_id'])->where('is_del_receive',0)->count('id');
        }

        $page_number = ceil($chat_record_count / $page_count);

        if ($data['page'] == 1) {
            $min = 0;
        } else {
            $min = ($data['page'] - 1) * $page_count;
        }


        // 标记此会话所有不是当前用户发的消息为已读->SQL不使用不等于->查得会话对象的ID
        Db::name('fzly_record')
            ->where('session_id', $data['session_id'])
            ->where('sender_id', $data['csr_id'])
            ->where('status', 0)
            ->update(['status' => 1]);

        if (Gateway::isUidOnline($data['csr_id'])) {
            Gateway::sendToUid($data['csr_id'], json_encode([
                'msgtype' => 'read_message_done',
                'data'    => [
                    'session_id' => $data['session_id'],
                    'record_id'  => 'all'
                ]
            ]));
        }

        if ($session_info['user_id'] == $data['user_id']){
            $chat_record = Db::name('fzly_record')
                ->where('session_id', $session_info['id'])
                ->where('is_del_sender',0)
                ->limit($min, $page_count)
                ->order('createtime desc,id desc')
                ->select();
        }else{
            $chat_record = Db::name('fzly_record')
                ->where('session_id', $session_info['id'])
                ->where('is_del_receive',0)
                ->limit($min, $page_count)
                ->order('createtime desc,id desc')
                ->select();
        }



        foreach ($chat_record as &$cv){
            $user = User::get($data['user_id']);
            $cv['user'] = [
                "id"=>$user['id'],
                "avatar"=>Common::imgSrcFill($user['avatar']),
                "username"=>$user['username'],
            ];

            $user = User::get($data['csr_id']);
            $cv['session_user'] = [
                "id"=>$user['id'],
                "avatar"=>Common::imgSrcFill($user['avatar']),
                "username"=>$user['username'],
            ];
        }

        if ($data['page'] == 1) {
            $chat_record = array_reverse($chat_record, false);
        }

        $tourists_record = false;


        // 消息按时间分组
        if ($chat_record && !$tourists_record) {

            $record_temp = [];
            $createtime  = $chat_record[0]['createtime'];

            foreach ($chat_record as $value) {

                if ($value['sender_id'] == $data['user_id']) {
                    $value['sender'] = 'me';
                } else {
                    $value['sender'] = 'you';
                }

                if ($value['message_type'] == 1 || $value['message_type'] == 2) {
                    $value['message'] = Common::imgSrcFill($value['message'], false);
                } else {
                    $value['message'] = htmlspecialchars_decode($value['message']);
                }

                if (($value['createtime'] - $createtime) < 3600) {
                    $record_temp[$createtime][] = $value;
                } else {
                    $createtime                 = $value['createtime'];
                    $record_temp[$createtime][] = $value;
                }
            }

            unset($chat_record);

            foreach ($record_temp as $key => $value) {
                $chat_record[] = [
                    'datetime' => Common::formatTime($key),
                    'data'     => $value,
                ];
            }
            unset($record_temp);
        } elseif ($tourists_record) {
            $chat_record = $tourists_record;
        } else {
            $chat_record[] = [
                'datetime' => '还没有消息',
                'data'     => [],
            ];
        }

        Gateway::sendToClient($client_id, json_encode([
            'msgtype' => 'chat_record',
            'code'    => 1,
            'data'    => [
                'chat_record'  => $chat_record,
                'next_page'    => ($data['page'] >= $page_number) ? 'done' : $data['page'] + 1,
                'page'         => $data['page'],
                'session_id'         => $session_info['id'],
            ],
        ]));
    }

    /*
     * 输入状态更新
     */
    public function messageInput($client_id, $data)
    {
        if (!isset($data['session_id'])) {
            common::showMsg($client_id, '缺少session_id');
            return '';
        }
        if (!isset($data['type'])) {
            common::showMsg($client_id, '缺少类型');
            return '';
        }
        if (!isset($data['user_id'])) {
            common::showMsg($client_id, '缺少user_id');
            return '';
        }
        if (!isset($data['csr_id'])) {
            common::showMsg($client_id, '缺少csr_id');
            return '';
        }

        $toMessage = [
            'msgtype' => 'message_input',
            'data'    => [
                'session_id' => $data['session_id'],
                'type'       => $data['type'],
            ]
        ];

        if (Gateway::isUidOnline($data['csr_id'])) {
            Gateway::sendToUid($data['csr_id'], json_encode($toMessage));
        }
        Gateway::sendToClient($client_id, json_encode([
            'msgtype' => 'message_input',
            'code'    => 1,
        ]));
    }

    /*
     * 直接标记消息已读
     */
    public function readMessage($client_id, $data)
    {
        if (isset($data['record_id']) && $data['record_id']) {
            // 查得会话信息
            $record = Db::name('fzly_record')
                ->where('id', (int)$data['record_id'])
                ->update(['status' => 1]);

            if ( $record && isset($data['session_id']) && $data['session_id']) {

                $session = Db::name('fzly_session')->where('id', (int)$data['session_id'])->find();
                if ($session) {
                    $re = Db::name('fzly_record')->where(['session_id'=>$data['session_id'],"receive_id"=>$data['user_id']])->find();
                    if (Gateway::isUidOnline($re['sender_id'])) {
                        Gateway::sendToUid($re['sender_id'], json_encode([
                            'msgtype' => 'read_message_done',
                            'data'    => [
                                'session_id' => $data['session_id'],
                                'record_id'  => $data['record_id'],
                                'sender_id'  => $re['sender_id'],
                            ]
                        ]));
                    }
                }
            }
        }else{
            common::showMsg($client_id, '缺少消息id');
            return '';
        }
    }

    /*
     * 删除会话
     */
    public function delSession($client_id, $data){
        if (!isset($data['session_id'])) {
            common::showMsg($client_id, '缺少session_id');
            return '';
        }
        if (!isset($data['user_id'])) {
            common::showMsg($client_id, '缺少用户id');
            return '';
        }
        $session = Session::get($data['session_id']);
        if ($session['user_id']==$data['user_id']){
            $session->is_del_user = 1;
            $session->is_top_user = 0;
            Record::where(['session_id'=>$data['session_id']])->update(['is_del_sender'=>1]);
        }else{
            $session->is_del_csr = 1;
            $session->is_top_csr = 0;
            Record::where(['session_id'=>$data['session_id']])->update(['is_del_receive'=>1]);

        }
        $session->save();

        Gateway::sendToClient($client_id, json_encode([
            'msgtype' => 'top',
            'code'    => 1,
        ]));
    }

    /*
     * 置顶
     */
    public function top($client_id, $data){
        if (!isset($data['session_id'])) {
            common::showMsg($client_id, '缺少session_id');
            return '';
        }
        if (!isset($data['user_id'])) {
            common::showMsg($client_id, '缺少用户id');
            return '';
        }
        if (!isset($data['type'])) {
            common::showMsg($client_id, '缺少type');
            return '';
        }
        $session = Session::get($data['session_id']);

        if ($data['type']==1){
//            \app\common\model\fzly\Session::where('user_id|csr_id',$data['user_id'])->update(['is_top_csr'=>0,'is_top_user'=>0]);

            if ($session['user_id']==$data['user_id']){
                $session->is_top_user = 1;
                $session->save();

            }else{
                $session->is_top_csr = 1;
                $session->save();

            }
        }else{
            if ($session['user_id']==$data['user_id']){
                $session->is_top_user = 0;
                $session->save();

            }else{
                $session->is_top_csr = 0;
                $session->save();

            }
        }


        Gateway::sendToClient($client_id, json_encode([
            'msgtype' => 'top',
            'code'    => 1,
        ]));
    }

    /*
     * 清除聊天记录
     */
    public function delRecord($client_id, $data){
        if (!isset($data['session_id'])) {
            common::showMsg($client_id, '缺少session_id');
            return '';
        }
        if (!isset($data['user_id'])) {
            common::showMsg($client_id, '缺少用户id');
            return '';
        }
        $session = Session::get($data['session_id']);
        if ($session['user_id']==$data['user_id']){
            Record::where(['session_id'=>$data['session_id']])->update(['is_del_sender'=>1]);
        }else{
            Session::where(['session_id'=>$data['session_id']])->update(['is_del_receive'=>1]);
        }
        Gateway::sendToClient($client_id, json_encode([
            'msgtype' => 'del_record',
            'code'    => 1,
        ]));
    }



}