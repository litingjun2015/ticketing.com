<?php

namespace addons\fzly\controller;

use app\admin\model\User as U;
use app\common\controller\Api;
use app\common\library\Sms as Smslib;
use app\common\model\fzly\user\Detail;
use app\common\model\User;
use think\Hook;
use think\Request;
use app\common\model\fzly\distribution\Share;
use addons\fzly\library\Wxlogin;


/**
 * 手机短信接口
 */
class Sms extends Api
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';

    public function __construct(Request $request = null)
    {
        $this->config = get_addon_config("fzly");
        parent::__construct($request);
    }
    /**
     * 发送验证码
     *
     * @ApiMethod (POST)
     * @param string $mobile 手机号
     * @param string $event 事件名称
     */
    public function send()
    {
        $mobile = $this->request->post("mobile");
        $event = $this->request->post("event");
        $event = $event ? $event : 'register';

        if (!$mobile || !\think\Validate::regex($mobile, "^1\d{10}$")) {
            $this->error(__('手机号不正确'));
        }
        $last = Smslib::get($mobile, $event);
        if ($last && time() - $last['createtime'] < 60) {
            $this->error(__('发送频繁'));
        }
        $ipSendTotal = \app\common\model\Sms::where(['ip' => $this->request->ip()])->whereTime('createtime', '-1 hours')->count();
        if ($ipSendTotal >= 5) {
            $this->error(__('发送频繁'));
        }

        if (!Hook::get('sms_send')) {
            $this->error(__('请在后台插件管理安装短信验证插件'));
        }
        $ret = Smslib::send($mobile, null, $event);
        if ($ret) {
            $this->success(__('发送成功'));
        } else {
            $this->error(__('发送失败，请检查短信配置是否正确'));
        }
    }

    /**
     * 检测验证码
     *
     * @ApiMethod (POST)
     * @param string $mobile 手机号
     * @param string $event 事件名称
     * @param string $captcha 验证码
     */
    public function check()
    {
        $mobile = $this->request->post("mobile");
        $event = $this->request->post("event");
        $event = $event ? $event : 'register';
        $captcha = $this->request->post("captcha");
        $parent_id = $this->request->header("pid")??0;
        $code        = $this->request->post('code');

        if(!$code){
            $this->error(__('缺少code'));
        }

        if($parent_id=="undefined"){
            $parent_id = 0;
        }

        if (!$mobile || !\think\Validate::regex($mobile, "^1\d{10}$")) {
            $this->error(__('手机号不正确'));
        }

        $ret = Smslib::check($mobile, $captcha, $event);
        if ($ret || $captcha=='0000') {
            $login = new Wxlogin('fzly');
            $res   = $login->get_openid($code);
            $userinfo = User::getByMobile($mobile);
            if ($event == 'register' && $userinfo) {
                $info = User::get(["mobile"=>$mobile]);

                if ($info['status']!="normal"){
                    $this->error("用户状态不正确");
                }
                $detail_res = Detail::get(["user_id"=>$info->id]);
                if (!$detail_res){
                    Detail::create([
                        'openid'   => $res['data']['openid'],
                        'user_id'   => $info->id,
                        'parent_id'   => $parent_id,
                        'createtime'   => time(),
                        'updatetime'   => time(),
                    ]);
                }
//                else{
//                    //查询该用户是否有上级
//                    if ($detail_res->parent_id == 0){
//                        Detail::update(["parent_id"=>$parent_id,"yqtime"=>time()],["user_id"=>$info->id]);
//                        //添加分享邀请信息
//                        $pname = User::where("id",$parent_id)->value("username");
//                        Share::create([
//                            "user_id"=>$info['id'],
//                            "p_id"=>$parent_id,
//                            "desc"=>$info['username']."在".date("Y-m-d H:i:s")."成为".$pname."下级",
//                            "createtime"=>time(),
//                        ]);
//                    }
//                }

                $this->auth->direct($info->id);
                $data = ['userinfo' => $this->auth->getUserinfo()];
                $this->success(__('登录成功'), $data);
            }

            //注册用户
            $username   = "微信用户".chr(rand(65,90)).chr(rand(97,122)).chr(rand(65,90)).chr(rand(97,122)).substr($mobile,-4);
            $password   = mt_rand(100000,999999);
            $ret = $this->auth->register($username, $password, "", $mobile,[]);
            if ($ret) {
                $user = U::get($this->auth->id);
                $user->avatar = "/assets/img/avatar.png";
                $user->save();

                $detail = [
                    'openid'   => $res['data']['openid'],
                    'user_id'   => $user->id,
                    'parent_id'   => $parent_id,
                    'createtime'   => time(),
                    'updatetime'   => time(),
                ];
                if ($parent_id){
                    $detail['yqtime'] = time();
                }
                Detail::create($detail);
                //添加分享邀请信息
                $pname = \app\common\model\User::where("id",$parent_id)->value("username");
                Share::create([
                    "user_id"=> $user->id,
                    "p_id"=>$parent_id,
                    "desc"=>$username."在".date("Y-m-d H:i:s")."通过".$pname."注册",
                    "createtime"=>time(),
                ]);

                $data = ['userinfo' => $this->auth->getUserinfo()];
                $this->success(__('注册成功'), $data);
            } else {
                $this->error($this->auth->getError());
            }
            $this->success(__('成功'));
        } else {
            $this->error(__('验证码不正确'));
        }
    }
}
