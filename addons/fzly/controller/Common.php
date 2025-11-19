<?php

namespace addons\fzly\controller;

use addons\fzly\library\SelectLngLatData;
use app\common\controller\Api;
use app\common\model\fzly\message\Push;
use addons\fzly\library\MpWechat;



class Common extends Api
{
    // 无需登录的接口,*表示全部
    protected $noNeedLogin = ['*'];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['*'];
    protected $config = [];

    public function __construct()
    {
        parent::__construct();
        $this->config = get_addon_config('fzly');
    }

    protected $getAuthCodeUrl = "https://open.weixin.qq.com/connect/oauth2/authorize";

    public function common()
    {
        $config = get_addon_config('fzly');

        $push = Push::all();
        foreach ($push as $k => &$v){
            if ($v['message_type'] == 1){
                $v['pay_masterplate'] = $v['masterplate'];
            }else{
                $v['ts_masterplate'] = $v['masterplate'];
            }
        }

        $data = [
            "app_name"          => $config["app_name"],        //小程序名称
            "timeout"           => $config["timeout"],        //订单超时时间
            "push"              => $push,                        //推送消息配置
            "app_filings"       =>  $config['app_filings'],            //APP备案号
            "mini_filings"      =>  $config['mini_filings'],            //小程序备案号
            "business_license"  =>  $config['business_license'],            //经营许可
            "mp_switch"  =>  $config['mp_switch'],            //买票开关
			"city"  =>    $config['city'],            //默认城市
            'mpurl' => (new MpWechat)->getAuthorizeUrl(),
            "fz_h5_title"       =>  $config["fz_h5_title"],          //公众号分享标题
            "fz_h5_intro"       =>  $config["fz_h5_intro"],         //公众号分享描述
            "fz_h5_url"         =>  $config["fz_h5_url"],         //公众号分享地址
            "fz_h5_fximg"       =>  cdnurl($config["fz_h5_fximg"],true),          //公众号分享图片
            'auth_type' => $config["auth_type"],
        ];
        $this->success("获取成功", $data);
    }

    /*
    * 城市列表
    */
    public function area(){
        $data['indexList'] = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        foreach ($data['indexList'] as $k => $v){
            $data['data'][$k]["id"] = $v;
            $datas = \app\common\model\Area::where(["level"=>2,"first"=>$v])->field("id,shortname")->where("shortname","<>",' ')->select();
            if ($datas){
                $datas = array_merge($datas);
            }
            if (empty($datas)){
                unset($data['indexList'][$k]);
                unset($data['data'][$k]);
            }else{
                $data['data'][$k]["value"] = $datas;
            }

        }
        $data['data'] = array_merge($data['data']);
        $data['indexList'] = array_merge($data['indexList']);
        $data['hot'] = explode(',',$this->config['hot_city']);
        $this->success("成功",$data);
    }

    /*
     * 搜索城市
     */
    public function search_area(){
        $search = $this->request->post("search")??"";
        if (!$search){
            $this->error("缺少搜索条件");
        }
        $data = \app\common\model\Area::where(["level"=>2])->where(function ($query)use($search){
            $query->whereor("shortname","like","%".$search."%");
            $query->whereor("first","like","%".$search."%");
        })->field("id,shortname")->select();
        $this->success("成功",$data);
    }

    /*
     * 省市
     */
    public function city(){
        $data = \app\common\model\Area::where(["level"=>1])->field("id,name")->select();
        foreach ($data as $k => $v){
            $v['child'] = \app\common\model\Area::where(["level"=>2,"pid"=>$v['id']])->field("id,name")->select();
        }
        $this->success("成功",$data);
    }

    /*
     * 获取经纬度地址
     */
    public function get_address(){
        $lat = $this->request->post('lat');
        $lng = $this->request->post('lng');
        if (!$lat || !$lng){
            $this->error("缺少经纬度");
        }

        $obj = SelectLngLatData::instance($lng, $lat);
        $address = $obj->getLongLatInfo();
        if (isset($address['code']) && $address['code'] == 0){
            $this->error($address['msg']);
        }
        $this->success("success",$address);
    }


}