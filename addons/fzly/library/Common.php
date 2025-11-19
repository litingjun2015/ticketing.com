<?php

namespace addons\fzly\library;

use app\admin\model\User;
use app\common\model\fzly\Record;
use app\common\model\fzly\Session;
use GatewayWorker\Lib\Gateway;
use think\Db;


/**
 *
 */
class Common
{

    public function __construct()
    {

    }

    /**
     * 发送消息
     * @param string client_id 链接ID
     * @param string msg 消息内容
     */
    public static function showMsg($client_id, $msg = '')
    {
        Gateway::sendToClient($client_id, json_encode([
            'code'    => 0,
            'msgtype' => 'show_msg',
            'msg'     => $msg,
        ]));
    }



    public static function sessionList($user_id, $limit)
    {

        // 读取会话列表
        // 查询置顶会话
        $top = Session::where('user_id|csr_id', $user_id)->where('deletetime', null)->select();
        if ($top){
            $top_id = 0;
            foreach ($top as $tv){
                if ($tv['user_id'] == $user_id){
                    if ($tv['is_top_user']){
                        $top_id = $tv['id'];
                        break;
                    }
                }
                if ($tv['csr_id'] == $user_id){
                    if ($tv['is_top_csr']){
                        $top_id = $tv['id'];
                        break;
                    }
                }
            }
            if ($top_id){
                //存在置顶的数据
                $top_data = Session::where('id', $top_id)->select()->toArray();
                $session = Session::where('user_id|csr_id', $user_id)
                    ->where('deletetime', null)
                    ->where('id',"<>",$top_id)
                    ->order('createtime desc')
                    ->limit($limit)
                    ->select()
                    ->toArray();
                $session = array_merge($top_data,$session);
            }else{
                $session = Session::where('user_id|csr_id', $user_id)
                    ->where('deletetime', null)
                    ->order('createtime desc')
                    ->limit($limit)
                    ->select()
                    ->toArray();
            }



            $total = 0;
            foreach ($session as $key => $value) {
                $session[$key]['is_top']   = 0;

                //筛选已经删除的会话
                if ($value['user_id'] == $user_id){
                    if ($value['is_top_user'] == 1){
                        $session[$key]['is_top']   = 1;
                    }
                    $user = User::get($value['csr_id']);
                    if ($value['is_del_user']==1){
                        unset($session[$key]);
                        continue;
                    }


                    // 最后一条聊天记录
                    $lastMessage = Record::where('session_id', $value['id'])
                        ->where("is_del_sender",0)
                        ->order('createtime desc')
                        ->find();
                }
                if ($value['csr_id'] == $user_id){

                    if ($value['is_top_csr'] == 1){
                        $session[$key]['is_top']   = 1;
                    }

                    $user = User::get($value['user_id']);

                    if ($value['is_del_csr']==1){
                        unset($session[$key]);
                        continue;
                    }


                    // 最后一条聊天记录
                    $lastMessage = Record::where('session_id', $value['id'])
                        ->where("is_del_receive",0)
                        ->order('createtime desc')
                        ->find();
                }
                $session[$key]['user_id']   = $user_id;
                $session[$key]['df_id']   = $user['id'];
                $session[$key]['df_user']   = $user['username'];
                $session[$key]['df_avatar']   = self::imgSrcFill($user['avatar']);
                $session[$key]['last_message']   = self::formatMessage($lastMessage);
                $session[$key]['last_timestamp'] = isset($lastMessage['createtime']) ? $lastMessage['createtime'] : null;
                $session[$key]['last_time']      = self::formatSessionTime($session[$key]['last_timestamp']);

                // 用户发来的未读消息数
                $msg_count = $session[$key]['unread_msg_count'] = Record::where('session_id', $value['id'])
                    ->where('receive_id', $user_id)
                    ->where('status', 0)
                    ->count('id');
                $total = $total+$msg_count;
            }
            $session = array_merge($session);

            return [$total,$session];
        }else{
            return [0,[]];
        }

    }

    /**
     * 获取图片的完整地址
     * @param string src 待处理的图片
     * @return string
     */
    public static function imgSrcFill($src, $avatar = false)
    {
        if (preg_match('/^http(s)?:\/\//', $src)) {
            return $src;
        }
        // $_SESSION['cdn_url'] 存在则代表当前为workerman环境
        $domain = isset($_SESSION['cdn_url']) ? $_SESSION['cdn_url'] : cdnurl('', true);
        return $src ? $domain . $src : $domain . ($avatar ? '/assets/img/avatar.png' : '/assets/img/blank.gif');
    }



    /*
     * 检查/过滤变量
     */
    public static function checkVariable(&$variable)
    {
        $variable = htmlspecialchars($variable);
        $variable = stripslashes($variable); // 删除反斜杠
        $variable = addslashes($variable); // 转义特殊符号
        $variable = trim($variable); // 去除字符两边的空格
    }

    /**
     * 格式化消息-将图片和连接用文字代替
     * @param array message 消息内容
     * @return string
     */
    public static function formatMessage($message)
    {
        if (!$message) {
            return '';
        }
        if ($message['message_type'] == 0 || $message['message_type'] == 3) {
            $message_text = htmlspecialchars_decode($message['message']);

            // 匹配所有的img标签
            $preg = '/<img.*?src=(.*?)>/is';
            preg_match_all($preg, $message_text, $result, PREG_PATTERN_ORDER);
            $message_text = str_replace($result[0], '[图片]', $message_text);
            $message_text = strip_tags($message_text);

        } elseif ($message['message_type'] == 1) {
            $message_text = '[图片]';
        }  else {
            $message_text = strip_tags($message['message']);
        }

        return $message_text;
    }

    /**
     * 格式化会话时间-按天顺时针格式化
     * @param int time 时间戳
     * @return string
     */
    public static function formatSessionTime($time = null)
    {
        if (!$time) {
            return date('H:i');
        }
        $now_date  = getdate(time());
        $time_date = getdate($time);

        if (($now_date['year'] === $time_date['year']) && ($now_date['yday'] === $time_date['yday'])) {
            return date('H:i', $time);
        } else {
            return self::formatTime($time);
        }
    }


    /**
     * 格式化时间-按时间差逆时针格式化
     * @param int time 时间戳
     * @return string
     */
    public static function formatTime($time = null)
    {
        $now_time = time();
        $time     = ($time === null || $time > $now_time || $time == $now_time) ? $now_time - 1 : intval($time);
        $lang     = [
            '%d second%s ago' => '%d秒前',
            '%d minute%s ago' => '%d分钟前',
            '%d hour%s ago'   => '%d小时前',
            '%d day%s ago'    => '%d天前',
            '%d week%s ago'   => '%d周前',
            '%d month%s ago'  => '%d月前',
            '%d year%s ago'   => '%d年前',
        ];
        \think\Lang::set($lang);
        $date = \fast\Date::human($time);
        return $date;
    }

    /**
     * 写入聊天记录/系统消息
     * @param int session_id 会话ID
     * @param string message 消息内容
     * @param string message_type 消息类型
     * @param string sender 带标识的发送人
     * @param string message_id 前台的消息ID-用于改变前台消息发送状态
     * @return array
     */
    public static function chatRecord($data)
    {
        $session = Session::where('id', $data['session_id'])->find();

        if (!$session) {
            return [
                'msgtype' => 'send_message',
                'code'    => 0,
                'data'    => [
                    'msg'        => '发送失败,会话找不到啦！',
                    'session_id' => $data['session_id']
                ]
            ];
        }
        Session::where('id', $data['session_id'])->update(['is_del_user'=>0,'is_del_csr'=>0]);

        // 发信人
        $sender_info = self::userInfo($data['user_id']);

        // 收信人信息
        if ($session['user_id'] == $data['user_id']){
            $receive = $session['csr_id'];
        }else{
            $receive = $session['user_id'];
        }
        $session_user_info = self::userInfo($receive);


        // 还原html
        $message_html = htmlspecialchars_decode($data['message']);

        // 去除样式
        $message_html = preg_replace("/style=.+?['|\"]/i", '', $message_html);
        $message_html = preg_replace("/width=.+?['|\"]/i", '', $message_html);
        $message_html = preg_replace("/height=.+?['|\"]/i", '', $message_html);



        $message = [
            'session_id'      => $data['session_id'],
            'sender_id'       => $sender_info['id'],
            'receive_id'       => $session_user_info['id'],
            'message'         => htmlspecialchars($message_html),// 入库的消息内容不解码
            'message_type'    => ($data['message_type'] == 'auto_reply') ? 0 : $data['message_type'],
            'status'          => 0,
            'createtime'      => time(),
        ];


        if (Record::create($message)) {
            $message['record_id'] = Record::getLastInsID(); //消息记录ID

            // 确定会话状态
            Session::where('id', $session['id'])->update([
                'deletetime' => null,
                'createtime' => time()
            ]);

            if (Gateway::isUidOnline($session_user_info['id'])) {
                Gateway::sendToUid($session_user_info['id'], json_encode(['msgtype' => 'new_message', 'data' => ["message"=>$data['message'],"user_id"=> $sender_info['id'],"avatar"=> $sender_info['avatar'],"session_user"=> $sender_info,"id"=> $message['record_id']]]));
            }

            return [
                'msgtype' => 'send_message',
                'code'    => 1,
                'data'    => [
                    'message' => $data['message'],
                    'user'=>$sender_info,
                    'avatar'=>$session_user_info['avatar'],
                    'session_user'=>$session_user_info,
                    'id'         => $message['record_id']
                ]
            ];
        } else {
            return [
                'msgtype' => 'send_message',
                'code'    => 0,
                'data'    => [
                    'message_id' => "",
                    'msg'        => '发送失败,请重试！'
                ]
            ];
        }
    }


    public static function removeXss($val)
    {
        if (function_exists('xss_clean')) {
            return xss_clean($val);
        }
        $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);

        $search = 'abcdefghijklmnopqrstuvwxyz';
        $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $search .= '1234567890!@#$%^&*()';
        $search .= '~`";:?+/={}[]-_|\'\\';
        for ($i = 0; $i < strlen($search); $i++) {
            $val = preg_replace('/(&#[xX]0{0,8}' . dechex(ord($search[$i])) . ';?)/i', $search[$i], $val); // with a ;
            $val = preg_replace('/(&#0{0,8}' . ord($search[$i]) . ';?)/', $search[$i], $val); // with a ;
        }

        $ra1   = [
            'javascript',
            'vbscript',
            'expression',
            'applet',
            'meta',
            'xml',
            'blink',
            'link',
            'style',
            'script',
            'embed',
            'object',
            'iframe',
            'frame',
            'frameset',
            'ilayer',
            'layer',
            'bgsound',
            'title',
            'base'
        ];
        $ra2   = [
            'onabort',
            'onactivate',
            'onafterprint',
            'onafterupdate',
            'onbeforeactivate',
            'onbeforecopy',
            'onbeforecut',
            'onbeforedeactivate',
            'onbeforeeditfocus',
            'onbeforepaste',
            'onbeforeprint',
            'onbeforeunload',
            'onbeforeupdate',
            'onblur',
            'onbounce',
            'oncellchange',
            'onchange',
            'onclick',
            'oncontextmenu',
            'oncontrolselect',
            'oncopy',
            'oncut',
            'ondataavailable',
            'ondatasetchanged',
            'ondatasetcomplete',
            'ondblclick',
            'ondeactivate',
            'ondrag',
            'ondragend',
            'ondragenter',
            'ondragleave',
            'ondragover',
            'ondragstart',
            'ondrop',
            'onerror',
            'onerrorupdate',
            'onfilterchange',
            'onfinish',
            'onfocus',
            'onfocusin',
            'onfocusout',
            'onhelp',
            'onkeydown',
            'onkeypress',
            'onkeyup',
            'onlayoutcomplete',
            'onload',
            'onlosecapture',
            'onmousedown',
            'onmouseenter',
            'onmouseleave',
            'onmousemove',
            'onmouseout',
            'onmouseover',
            'onmouseup',
            'onmousewheel',
            'onmove',
            'onmoveend',
            'onmovestart',
            'onpaste',
            'onpropertychange',
            'onreadystatechange',
            'onreset',
            'onresize',
            'onresizeend',
            'onresizestart',
            'onrowenter',
            'onrowexit',
            'onrowsdelete',
            'onrowsinserted',
            'onscroll',
            'onselect',
            'onselectionchange',
            'onselectstart',
            'onstart',
            'onstop',
            'onsubmit',
            'onunload'
        ];
        $ra    = array_merge($ra1, $ra2);
        $found = true;
        while ($found == true) {

            $val_before = $val;
            for ($i = 0; $i < sizeof($ra); $i++) {
                $pattern = '/';
                for ($j = 0; $j < strlen($ra[$i]); $j++) {
                    if ($j > 0) {
                        $pattern .= '(';
                        $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                        $pattern .= '|';
                        $pattern .= '|(&#0{0,8}([9|10|13]);)';
                        $pattern .= ')*';
                    }
                    $pattern .= $ra[$i][$j];
                }
                $pattern     .= '/i';
                $replacement = substr($ra[$i], 0, 2) . '<k>' . substr($ra[$i], 2);
                $val         = preg_replace($pattern, $replacement, $val);
                if ($val_before == $val) {
                    $found = false;
                }
            }
        }
        return $val;
    }


    /**
     * 获取用户信息
     * @param string user 带标识符的用户id
     * @return array
     */
    public static function userInfo($user)
    {
        $userInfo = User::get($user);
        $user_info['id']           = $userInfo['id'];
        $user_info['source']   = 'user';

        $user_info['username']     = $userInfo['username'];
        $user_info['avatar']       = self::imgSrcFill($userInfo['avatar'],true);
        $user_info['session_type'] = 1;
        return $user_info;

    }

    /**
     * 检查FastAdmin用户token
     * @param string token 用户的token信息
     * @return int 用户ID
     */
    public static function checkFaUser($token)
    {
        $cookie_httponly = config('cookie.httponly');
        if (!$cookie_httponly) {
            $user_id = Db::name('user_token')->where('token', Common::getEncryptedToken($token))->value('user_id');
        } else {
            list($id, $key) = explode('|', $token);
            $user_token_list = Db::name('user_token')
                ->where('user_id', $id)
                ->where('expiretime', '>', time())
                ->select();
            foreach ($user_token_list as $user_token) {
                $sign     = $user_token['token'] . 'fzly_user_sign_additional';
                $user_key = md5(md5($id) . md5($sign));
                if ($user_key == $key) {
                    $user_id = $id;
                    break;
                } else {
                    $user_id = false;
                }
            }
        }

        return $user_id;
    }

    /**
     * 用户token加密
     * @param string $token 待加密的token
     */
    public static function getEncryptedToken($token)
    {
        $token_config = \think\Config::get('token');

        $config = [
            // 缓存前缀
            'key'      => $token_config['key'],
            // 加密方式
            'hashalgo' => $token_config['hashalgo'],
        ];

        return hash_hmac($config['hashalgo'], $token, $config['key']);
    }



    /**
     * 获取上传资源的CDN的地址
     * @param string  $url    资源相对地址
     * @param boolean $domain 是否显示域名 或者直接传入域名
     * @return string
     */
     public static function fzcdnurl($url, $domain = false)
        {
            $regex = "/^((?:[a-z]+:)?\/\/|data:image\/)(.*)/i";
            $cdnurl = \think\Config::get('upload.cdnurl');
            $url = preg_match($regex, $url) || ($cdnurl && stripos($url, $cdnurl) === 0) ? $url : $cdnurl . $url;
            if ($domain && !preg_match($regex, $url)) {
                $domain = is_bool($domain) ? request()->domain() : $domain;
                $url = $domain . $url;
            }
            return $url;
        }



}
