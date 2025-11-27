<?php

namespace addons\fzly\library;

use think\Log;

class Wxlogin
{
    private $appid;
    private $sessionKey;

    /**
     * 构造函数
     * @param $sessionKey string 用户在小程序登录后获取的会话密钥
     * @param $appid string 小程序的appid
     */

    public function __construct($addons = "")
    {
        $config = get_addon_config($addons);
        $appId = $config['appid'];
        $secret = $config['secret'];
        $this->sessionKey = $secret;
        $this->appid = $appId;
    }

    /**
     * 微信登录
     * @return array 成功0，失败返回对应的错误码
     */

    public function login($code,$encryptdata,$iv)
    {
        // 根据拿的code来拿access_token
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$this->appid}&secret={$this->sessionKey}&js_code={$code}&grant_type=authorization_code";
        $return = $this->https_request($url);
        $jsonrt = json_decode($return, true);
        if (isset($jsonrt['errcode'])) {
            return ["code"=>0,"msg"=>"微信授权发生错误:{$jsonrt['errmsg']},错误代码：" . $jsonrt['errcode']];
        }

        $sessionKey = $jsonrt['session_key'] ?? '';
        // 根据encryptdata 和 iv 解密获得用户的基本信息
        $pc = new WxBizDataCrypt($this->appid, $sessionKey);
        $errCode = $pc->decryptData($encryptdata, $iv, $data);
        if ($errCode != 0) {
            return ["code"=>0,"msg"=>"数据解析错误，代码：" . $errCode];
        }

        $userInfo = json_decode($data,true);
        $openid = $jsonrt['openid'];

        $mobile = isset($userInfo['phoneNumber'])?$userInfo['phoneNumber']:"";

        return ["code"=>1,"data"=>['mobile' => $mobile,  'openid' => $openid]];
    }


    /**
     * 微信登录
     * @return array 成功0，失败返回对应的错误码
     */
    public function logins($code,$encryptdata,$iv)
    {
        // 根据拿的code来拿access_token
        $url = "https://api.weixin.qq.com/cgi-bin/token?appid={$this->appid}&secret={$this->sessionKey}&js_code={$code}&grant_type=client_credential";
        $return = $this->https_request($url);
        $jsonrt = json_decode($return, true);
        if (isset($jsonrt['errcode'])) {
            return ["code"=>0,"msg"=>"微信授权发生错误:{$jsonrt['errmsg']},错误代码：" . $jsonrt['errcode']];
        }
        $access_token = $jsonrt['access_token'] ?? '';
        $new_url = "https://api.weixin.qq.com/wxa/business/getuserphonenumber";
        $res = json_decode($this->sendCmd($new_url, ["access_token"=>$access_token,"code"=>$code]),true);
        Log::info("请求返回结果：".json_encode($res));
        return ["code"=>1,"data"=>['mobile' => $res['phone_info']['purePhoneNumber']]];
    }


    /**
     * 微信登录
     * @return array 成功0，失败返回对应的错误码
     */
    public function get_openid($code)
    {
        // 根据拿的code来拿access_token
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$this->appid}&secret={$this->sessionKey}&js_code={$code}&grant_type=authorization_code";
        $return = $this->https_request($url);
        $jsonrt = json_decode($return, true);
        if (isset($jsonrt['errcode'])) {
            return ["code"=>0,"msg"=>"微信授权发生错误:{$jsonrt['errmsg']},错误代码：" . $jsonrt['errcode']];
        }
        $openid = $jsonrt['openid'];
        return ["code"=>1,"data"=>['openid' => $openid]];

    }





    public function sendCmd($url, $data)
    {
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检测
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Expect:')); //解决数据包大不能提交
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($curl); // 执行操作


        if (curl_errno($curl)) {
            echo 'Errno' . curl_error($curl);
        }
        curl_close($curl); // 关键CURL会话
        return $tmpInfo; // 返回数据
    }


    function https_request($url, $data = null)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

}





