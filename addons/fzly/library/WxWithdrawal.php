<?php
/**
 * 微信=>商家付款到个人
 */
namespace addons\fzly\library;
use think\Exception;
header("Content-type:text/html;charset=utf-8");

class WxWithdrawal{

    public function __construct()
    {
        $addon_info = get_addon_info('epay');
        if (!$addon_info) {
            throw new Exception('插件没有安装！');
        }
        $this->fzpay = get_addon_config('epay');
        $this->ly_info = get_addon_config('fzly');

    }
    /**
     * 查找支付账单
     * @param  need_query_detail 【是否查询转账明细单】 true-是；false-否，默认否。商户可选择是否查询指定状态的转账明细单，当转账批次单状态为“FINISHED”（已完成）时，才会返回满足条件的转账明细单
     * @param offset 请求资源起始位置】 该次请求资源的起始位置。返回的明细是按照设置的明细条数进行分页展示的，一次查询可能无法返回所有明细，我们使用该参数标识查询开始位置，默认值为0
     * @param limit 【最大资源条数】 该次请求可返回的最大明细条数，最小20条，最大100条，不传则默认20条。不足20条按实际条数返回
     * @param detail_status 【明细状态】 WAIT_PAY: 待确认。待商户确认, 符合免密条件时, 系统会自动扭转为转账中
     *ALL:全部。需要同时查询转账成功和转账失败的明细单
     *SUCCESS:转账成功
     *FAIL:转账失败。需要确认失败原因后，再决定是否重新发起对该笔明细单的转账（并非整个转账批次单）
     * **/

    public function wx_batch($result){
        $post_data = [
            "need_query_detail" => true,
            "offset" => 0,
            "limit" => 100,
            "detail_status" =>'ALL'
        ];
        array_walk_recursive($post_data, static function (&$val) {
            is_bool($val) && $val = $val ? 'true' : 'false';
        });

        $url_params = http_build_query($post_data);
        $url = "https://api.mch.weixin.qq.com/v3/transfer/batches/batch-id/{$result['out_batch_no']}?".$url_params;
        $result = $this->wx_request($url, '' ,'GET');
        $result = json_decode($result, true);

        return $result;
    }

    /**
     * 发起支付申请
     * **/
    public function wx_withdrawal($openid,$partner_trade_no,$amount){

        $money = (int)($amount * 100); // 提现金额
        $post_data = [
            "appid" => $this->fzpay['wechat']['app_id'],//appid
            "out_batch_no" => $partner_trade_no,//商家批次单号
            "batch_name" => date("Y-m-d H:i:s").'提现',//批次名称
            "batch_remark" => '用户提现',//批次备注
            "total_amount" => $money,// 转账金额单位为“分”
            "total_num" => 1, // 转账总笔数
            'transfer_scene_id' => "1001",
            //此处可以多笔提现  组合二维数组放到transfer_detail_list即可
            "transfer_detail_list" => [
                [
                    'out_detail_no' => $partner_trade_no,
                    'transfer_amount' => $money,
                    'transfer_remark' => '用户提现',
                    'openid' => $openid,
                ]
            ]
        ];
        $url = 'https://api.mch.weixin.qq.com/v3/transfer/batches';
        //JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE  防止中文被转义
        $result = $this->wx_request($url, json_encode($post_data, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));
        $result = json_decode($result, true);
        if (isset($result['prepay_id'])) {
            return $result['prepay_id'];
        }
        return $result;
    }


    public function ali_withdrawal($openid,$partner_trade_no,$amount){

        $alipay_root_cert = str_ireplace("/addons/epay",'./' ,$this->fzpay['alipay']['alipay_root_cert']);
        $app_cert_public_key = str_ireplace("/addons/epay",'./' ,$this->fzpay['alipay']['app_cert_public_key']);
        $ali_public_key = str_ireplace("/addons/epay",'./' ,$this->fzpay['alipay']['ali_public_key']);

        $privateKey = $this->fzpay['alipay']['private_key'];
        $alipayConfig = new \AlipayConfig();
        $alipayConfig->setPrivateKey($privateKey);
        $alipayConfig->setServerUrl("https://openapi.alipay.com/gateway.do");
        $alipayConfig->setAppId($this->fzpay['alipay']['app_id']);
        $alipayConfig->setCharset("UTF-8");
        $alipayConfig->setSignType("RSA2");
        $alipayConfig->setEncryptKey("");
        $alipayConfig->setFormat("json");
        $alipayConfig->setAppCertPath($app_cert_public_key);
        $alipayConfig->setAlipayPublicCertPath($ali_public_key);
        $alipayConfig->setRootCertPath($alipay_root_cert);
        $alipayClient = new \AopCertClient($alipayConfig);
        $alipayClient->isCheckAlipayPublicCert = true;
        $request = new \AlipayFundTransUniTransferRequest();

        $alData['out_biz_no'] = $partner_trade_no;
        $alData['remark'] = '用户提现';
        $alData['business_params']['payer_show_name_use_alias'] = true;
        $alData['biz_scene'] = 'DIRECT_TRANSFER';
        $alData['payee_info']['identity'] = $openid;
        $alData['payee_info']['identity_type'] = "ALIPAY_OPEN_ID";
        $alData['trans_amount'] = $amount;
        $alData['product_code'] = 'TRANS_ACCOUNT_NO_PWD';
        $alData['order_title'] = '用户提现';
        $request->setBizContent(json_encode($alData));
        $responseResult = $alipayClient->execute($request);


        $responseApiName = str_replace(".","_",$request->getApiMethodName())."_response";
        $response = $responseResult->$responseApiName;
        if(!empty($response->code)&&$response->code==10000){
            $result['order_id'] = $response->order_id;
            $result['out_biz_no'] = $response->out_biz_no;
            $result['pay_fund_order_id'] = $response->pay_fund_order_id;
            $result['code'] = $response->code;
        }
        else{
            $result['message'] = $response->sub_msg;
            $result['code'] = $response->code;
        }


        return $result;
    }





    private function wx_request($url, $param , $method = 'POST')
    {
        $authorization = $this->getV3Sign($url, $method, $param);
        $curl = curl_init();
        $headers = [
            'Authorization:' . $authorization,
            'Accept:application/json',
            'Content-Type:application/json;charset=utf-8',
            'User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36',
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        if($method=="POST"){
            curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
            curl_setopt($curl, CURLOPT_POST, true);
        }
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }

    private function getV3Sign($url, $http_method, $body)
    {
        $nonce = strtoupper($this->createNonceStr(32));
        $timestamp = time();
        $url_parts = parse_url($url);
        $canonical_url = ($url_parts['path'] . (!empty($url_parts['query']) ? "?${url_parts['query']}" : ""));
        $sslKeyPath = $this->fzpay['wechat']['cert_key'];
        //拼接参数
        $message = $http_method . "\n" .
            $canonical_url . "\n" .
            $timestamp . "\n" .
            $nonce . "\n" .
            $body . "\n";
        $private_key = $this->getPrivateKey(ROOT_PATH.$sslKeyPath);
        openssl_sign($message, $raw_sign, $private_key, 'sha256WithRSAEncryption');
        $sign   = base64_encode($raw_sign);
        $token = sprintf('WECHATPAY2-SHA256-RSA2048 mchid="%s",nonce_str="%s",timestamp="%s",serial_no="%s",signature="%s"',  $this->fzpay['wechat']['mch_id'], $nonce, $timestamp,  $this->ly_info['wechat_cert_serial_no'], $sign);
        return $token;
    }

    /**
     * 生成随机32位字符串
     * @param $length
     * @return string
     */
    public function createNonceStr($length = 16) { //生成随机16个字符的字符串
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 获取私钥
     * @param $filepath
     * @return false|resource
     */
    private function getPrivateKey($filepath = '')
    {
        if (empty($filepath)) {
            //私钥位置
            $filepath =  $this->fzpay['wechat']['cert_client'];
        }
        return openssl_get_privatekey(file_get_contents($filepath));
    }
}
