<?php
namespace addons\fzly\library;

class SendMessage
{
    protected $appid = '';
    protected $secret = '';
    protected $dy_appid = '';
    protected $dy_secret = '';


    public function __construct()
    {
        $this->config = get_addon_config("fzly");
        $this->appid  = $this->config['appid'];
        $this->secret = $this->config['secret'];
    }

    /*
     * 微信发送订阅消息
     */
    public function send_temp($openid, $templateId,$params)
    {

        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appid.'&secret='.$this->secret;
        //小程序信息获取token
        $res = $this->curl_get($url);
        $data = json_decode($res, true);

        if (isset($data['access_token'])) {
            $send_url = 'https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token=' . $data['access_token'];
            $send_data = [
                'access_token'          => $data['access_token'],//接口调用凭证
                'touser'                => $openid,              //openid
                'template_id'           => $templateId,          //所需下发的订阅模板id
                'sn'                    => '',          //设备唯一序列号
                'modelId'               => '',          //设备型号 id
                'data'                  => $params
            ];

            $send_data_decode = json_encode($send_data, true);
            return json_decode($this->sendCmd($send_url, $send_data_decode),true);
        }
    }

    /*
     * 抖音发布订阅消息
     */
    public function dou_send($params)
    {
        $url = 'https://developer.toutiao.com/api/apps/v2/token';
        //小程序信息获取token
        $data = [
            "appid"=>$this->dy_appid,
            "secret"=>$this->secret,
            "grant_type"=>"client_credential",
        ];
        $data = json_encode($data, true);
        $res = $this->sendCmd($url,$data);
        $data = json_decode($res, true);

        if (isset($data['data']['access_token'])) {
            $send_url = 'https://developer.toutiao.com/api/apps/subscribe_notification/developer/v1/notify';
            $params['access_token'] = $data['data']['access_token'];
            $send_data_decode = json_encode($params, true);
            return json_decode($this->sendCmd($send_url, $send_data_decode),true);
        }
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

    function curl_get($url){

        $header = array(
            'Accept: application/json',
        );
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        // 超时设置,以秒为单位
        curl_setopt($curl, CURLOPT_TIMEOUT, 1);

        // 超时设置，以毫秒为单位
        // curl_setopt($curl, CURLOPT_TIMEOUT_MS, 500);

        // 设置请求头
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        //执行命令
        $data = curl_exec($curl);

        // 显示错误信息
        if (curl_error($curl)) {
            print "Error: " . curl_error($curl);
        } else {
            // 打印返回的内容
            curl_close($curl);
            return $data;

        }
    }
}