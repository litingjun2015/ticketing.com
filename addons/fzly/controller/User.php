<?php

namespace addons\fzly\controller;

use addons\fzly\library\Wechat;
use addons\fzly\library\Wxlogin;
use app\common\model\Area;
use app\common\model\fzly\admission\Admissionuser;
use app\common\model\fzly\attractions\Attractions;
use app\common\model\fzly\feedback\Feedback;
use app\common\model\fzly\guide\Type;
use app\common\model\fzly\house\Houseuser;
use app\common\model\fzly\pact\Pact;
use app\common\model\fzly\trends\Trends;
use app\common\model\fzly\user\Bank;
use app\common\model\fzly\user\Detail;
use app\common\controller\Api;
use app\admin\model\User as U;
use app\common\model\fzly\user\Dz;
use app\common\model\fzly\user\Follow;
use app\common\model\fzly\user\guide\Examine;
use app\common\model\fzly\user\guide\Product;
use app\common\model\fzly\user\Hitory;
use app\common\model\fzly\user\Sc;
use app\common\model\fzly\user\Travel;
use DateInterval;
use DateTime;
use think\Log;
use think\Model;
use think\Request;
use think\Validate;
use addons\fzly\library\MpWechat;
use addons\fzly\library\Jssdk;
use app\common\model\fzly\distribution\Share;


class User extends Api
{

    protected $noNeedLogin = ['login','pact','getjssdk','loginWxOfficial','getjmlogin','userInfo'];
    protected $noNeedRight = ['*'];
    protected $config = [];

    public function __construct(Request $request = null)
    {
        $this->config = get_addon_config("fzly");
        parent::__construct($request);
    }

    /*
     * 注册/登录
     */
    public function login(){
        $encryptdata = $this->request->post('encryptedData','','trim');
        $iv          = $this->request->post('iv');
        $code        = $this->request->post('code');
        $parent_id = $this->request->header("pid")??0;
        if (!$code || !$encryptdata || !$iv) {
            $this->error('请求数据不能为空');
        }
        $iv = urldecode($iv);
        $encryptdata = urldecode($encryptdata);
        //微信授权登录
        $login = new Wxlogin('fzly');
        $res   = $login->login($code,$encryptdata,$iv);
        if (!$res["code"]){
            $this->error($res['msg']);
        }
        $openId = $res['data']['openid'];
        $mobile = $res['data']['mobile'];
        //查询用户表是否存在此open_id,存在则登录,不存在则注册
        $user = Detail::get(["openid"=>$openId]);
        if ($user){
            //登录
            $u_info = U::get($user->user_id);
            if ($u_info['status']!="normal"){
                $this->error("用户状态不正确");
            }
            $ret = $this->auth->direct($user->user_id);
            if ($ret) {
                $data = ['userinfo' => $this->auth->getUserinfo()];
                //查询该用户是否有上级
//                if ($user->parent_id == 0 && $parent_id){
//                    $user->parent_id = $parent_id;
//                    $user->yqtime = time();
//                    $user->save();
//                    //添加分享邀请信息
//                    $pname = \app\common\model\User::where("id",$parent_id)->value("username");
//                    Share::create([
//                        "user_id"=>$user['id'],
//                        "p_id"=>$parent_id,
//                        "desc"=>$u_info['username']."在".date("Y-m-d H:i:s")."成为".$pname."下级",
//                        "createtime"=>time(),
//                    ]);
//                }
                $this->success("登录成功", $data);
            } else {
                $this->error($this->auth->getError());
            }
        }else{
            //注册
            $username   = "微信用户".chr(rand(65,90)).chr(rand(97,122)).chr(rand(65,90)).chr(rand(97,122)).substr($mobile,-4);
            $password   = mt_rand(100000,999999);
            //判断用户是否用手机号注册过 如果注册过 绑定微信号
            $info = U::get(["mobile"=>$mobile]);
            if ($info){
                if ($info['status']!="normal"){
                    $this->error("用户状态不正确");
                }
                //更新用户表中的openid
                $detail_res = Detail::get(["user_id"=>$info->id]);
                if ($detail_res){
                    $update_data = [
                        'openid'   => $openId,
                        'updatetime'   => time(),
                    ];
//                    if ($detail_res->parent_id == 0 && $parent_id){
//                        $update_data['parent_id'] = $parent_id;
//                        $update_data['yqtime'] = time();
//                        //添加分享邀请信息
//                        $pname = \app\common\model\User::where("id",$parent_id)->value("username");
//                        Share::create([
//                            "user_id"=>$info['id'],
//                            "p_id"=>$parent_id,
//                            "desc"=>$info['username']."在".date("Y-m-d H:i:s")."成为".$pname."下级",
//                            "createtime"=>time(),
//                        ]);
//                    }
                    Detail::update(["openid"=>$openId],$update_data);
                }else{
                    Detail::create([
                        'openid'   => $openId,
                        'user_id'   => $info->id,
                        'parent_id'   => $parent_id,
                        'createtime'   => time(),
                        'updatetime'   => time(),
                    ]);
//                    //添加分享邀请信息
//                    $pname = \app\common\model\User::where("id",$parent_id)->value("username");
//                    Share::create([
//                        "user_id"=>$info['id'],
//                        "p_id"=>$parent_id,
//                        "desc"=>$info['username']."在".date("Y-m-d H:i:s")."通过".$pname."注册",
//                        "createtime"=>time(),
//                    ]);
                }
                $this->auth->direct($info->id);
                $data = ['userinfo' => $this->auth->getUserinfo()];
                $this->success("登录成功", $data);
            }else{
                $ret = $this->auth->register($username, $password, "", $mobile, []);
                if ($ret) {
                    $user = U::get($this->auth->id);
                    $user->avatar = "/assets/img/avatar.png";
                    $user->save();

                    $detail = [
                        'openid'   => $openId,
                        'user_id'   => $user->id,
                        'createtime'   => time(),
                        'updatetime'   => time(),
                    ];
                    //查询该用户是否有上级
                    if ($parent_id){
                        $detail['parent_id'] = $parent_id;
                        $detail['yqtime'] = time();
                        //添加分享邀请信息
                        $pname = \app\common\model\User::where("id",$parent_id)->value("username");
                        Share::create([
                            "user_id"=>$this->auth->id,
                            "p_id"=>$parent_id,
                            "desc"=>$username."在".date("Y-m-d H:i:s")."通过".$pname."注册",
                            "createtime"=>time(),
                        ]);
                    }
                    Detail::create($detail);


                    $data = ['userinfo' => $this->auth->getUserinfo()];
                    $this->success(__('注册成功'), $data);
                } else {
                    $this->error($this->auth->getError());
                }
            }
        }
    }

    /*
     * 公众号注册/登录
     */
    public function loginWxOfficial(){
        $platform = $this->request->header('platform', 'wechat');
        $parent_id = $this->request->header("pid")??0;
        $code        = $this->request->post('code');
        Log::info("获取用户信息参数：code=".$code);

        $mpwechat = new MpWechat();
        $param['code'] = $code;
        $backuserinfo = $mpwechat->getUserInfo($param);
        Log::info("获取用户信息返回结果：".json_encode($backuserinfo));
    // print_r($backuserinfo);die;
        if (!$backuserinfo) {
            $this->error(__('操作失败'));
        }
        $openId = $backuserinfo['openid'];
        $mobile = '';
        //查询用户表是否存在此open_id,存在则登录,不存在则注册
        $user = Detail::get(["openid"=>$openId]);
        if ($user){
            //登录
            $u_info = U::get($user->user_id);
            if ($u_info['status']!="normal"){
                $this->error("用户状态不正确");
            }
            $ret = $this->auth->direct($user->user_id);
            if ($ret) {
                //查询该用户是否有上级
                if ($user->parent_id == 0 && $parent_id){
                    $user->parent_id = $parent_id;
                    $user->yqtime = time();
                    $user->save();
                    //添加分享邀请信息
                    $pname = \app\common\model\User::where("id",$parent_id)->value("username");
                    Share::create([
                        "user_id"=>$user['id'],
                        "p_id"=>$parent_id,
                        "desc"=>$u_info['username']."在".date("Y-m-d H:i:s")."成为".$pname."下级",
                        "createtime"=>time(),
                    ]);
                }
                $data = ['userinfo' => $this->auth->getUserinfo()];
                $this->success("登录成功", $data);
            } else {
                $this->error($this->auth->getError());
            }
        }else{
            //注册
            $username   = $backuserinfo['userinfo']['nickname'].chr(rand(65,90)).chr(rand(97,122)).chr(rand(65,90)).chr(rand(97,122)).substr($mobile,-4);
            $password   = mt_rand(100000,999999);
            //判断用户是否用手机号注册过 如果注册过 绑定微信号
//            $info = U::get(["mobile"=>$mobile]);
//            if ($info){
//                if ($info['status']!="normal"){
//                    $this->error("用户状态不正确");
//                }
//                //更新用户表中的openid
//                $detail_res = Detail::get(["user_id"=>$info->id]);
//                if ($detail_res){
//                    Detail::update(["openid"=>$openId],["user_id"=>$info->id]);
//                }else{
//                    Detail::create([
//                        'openid'   => $openId,
//                        'user_id'   => $info->id,
//                        'createtime'   => time(),
//                        'updatetime'   => time(),
//                    ]);
//                }
//                $this->auth->direct($info->id);
//                $data = ['userinfo' => $this->auth->getUserinfo()];
//                $this->success("登录成功", $data);
//            }else{
                $ret = $this->auth->register($username, $password, "", $mobile, []);
                if ($ret) {
                    $user = U::get($this->auth->id);
                    $user->avatar = $backuserinfo['userinfo']['avatar'] ? $backuserinfo['userinfo']['avatar'] :  '/assets/img/avatar.png';
                    $user->save();

                    $detail = [
                        'openid'   => $openId,
                        'user_id'   => $user->id,
                        'createtime'   => time(),
                        'updatetime'   => time(),
                    ];
                    //查询该用户是否有上级
                    if ($parent_id){
                        $detail['parent_id'] = $parent_id;
                        $detail['yqtime'] = time();
                        //添加分享邀请信息
                        $pname = \app\common\model\User::where("id",$parent_id)->value("username");
                        Share::create([
                            "user_id"=>$this->auth->id,
                            "p_id"=>$parent_id,
                            "desc"=>$username."在".date("Y-m-d H:i:s")."通过".$pname."注册",
                            "createtime"=>time(),
                        ]);
                    }
                    Detail::create($detail);


                    $data = ['userinfo' => $this->auth->getUserinfo()];
                    $this->success(__('注册成功'), $data);
                } else {
                    $this->error($this->auth->getError());
                }
            }
//        }
    }

    public function getjssdk(){
        $config = get_addon_config('fzly');
        $urls = $this->request->post('urls');
        $fzwechat =  new Jssdk($config['wechat']['app_id'] ,$config['wechat']['app_secret'] );
        $data['signData'] =$fzwechat->GetSignPackage($urls);
        $this->success(__('成功'), $data);

    }

    public function getjmlogin(){
        $urls = $this->request->post('urls');
        $data['url'] =  (new MpWechat)->getAuthorizeUrl($urls);
        $this->success(__('成功'), $data);

    }

    /*
    * 获取会员个人信息
    */
    public function userInfo()
    {
        $user = $this->auth->id;
        $data = U::where("id",$user)->field("id,username,mobile,avatar,email,birthday,gender,bio")->find();
        if (!$data){
            $this->error("用户不存在");
        }
        $detail = Detail::where(["user_id"=>$user])->field("openid,back_avatar,constellation,label,real_name,like_s,gz_s,fs_s,view_s,is_dy,c_city")->find();
        if (!$detail){
            $this->error("用户信息不存在");
        }
        if ($detail['c_city']){
            if (strpos($detail['c_city'],'/')!==false){
                $detail['c_city'] = explode('/',$detail['c_city'])[1];
            }
        }
        $data = array_merge($data->toArray(),$detail->toArray());
        $data['avatar'] = $data['avatar']?cdnurl($data['avatar'],true):cdnurl("/assets/img/avatar.png",true);
        $data['back_avatar'] = $data['back_avatar']?cdnurl($data['back_avatar'],true):cdnurl("/assets/img/avatar.png",true);
        $dy_res = Examine::where(["user_id"=>$user])->order("id","desc")->find();

        if ($dy_res){
            $data['is_dy'] = $detail['is_dy']==1 ? 2 : 1;
        }else{
            $data['is_dy'] = -1;
        }

//        $data['is_dy'] = $detail['is_dy']==1 ? 2 : 1;
        //判断该账号是否有门票管理权限
        $data['is_admissionuser'] = -1;
        $user_admission = Attractions::get(["user_id"=>$user]);
        if ($user_admission){
            $user_admission['image'] = cdnurl($user_admission['image'],true);
            $user_admission['yy_image'] = cdnurl($user_admission['yy_image'],true);
            $data['admissionuser'] = $user_admission;
            $data['is_admissionuser'] = $user_admission['status'];
        }

        $this->success("获取成功",$data);
    }

    /*
     * 景区列表接口
     */
    public function guideList()
    {
        $data = Attractions::where(["status"=>'normal'])->order("id desc")->select();
        $this->success("获取成功",$data);
    }

    /*
     * 门票端入驻申请
     */
    public function admission()
    {
        $user_id = $this->auth->id;
        $name = $this->request->post("name");
        $tel = $this->request->post("tel");
        $card = $this->request->post("card");
        $yy_image = $this->request->post("yy_image");
        $admission_title = $this->request->post("admission_title");
        $admission_city = $this->request->post("admission_city");
        $admission_image = $this->request->post("admission_image");
        if (!$name){
            $this->error("姓名不能为空");
        }
        if (!$tel) {
            $this->error("手机号不能为空");
        }
        if (!preg_match("/^1[345789]\d{9}$/", $tel)) {
            $this->error("手机号格式不正确");
        }
        if (!$card) {
            $this->error("身份证号不能为空");
        }
        if (!$yy_image) {
            $this->error("营业执照照片不能为空");
        }
        if (!$admission_title) {
            $this->error("景区不能为空");
        }
        if (!$admission_city) {
            $this->error("景区城市不能为空");
        }
        if (!$admission_image) {
            $this->error("景区缩略图不能为空");
        }


        $user_admission = Attractions::get(["user_id"=>$user_id]);
        if (isset($user_admission['status']) && $user_admission['status']==0){
            $this->error("已申请过，请耐心等待审核");
        }elseif (isset($user_admission['status']) && $user_admission['status']==1){
            $this->error("已通过审核，无需重复申请");
        }elseif (isset($user_admission['status']) && $user_admission['status']==2){
            $data = [
                "name" => $name,
                "tel" => $tel,
                "card" => $card,
                "yy_image" => $yy_image,
                "title"=> $admission_title,
                "city"=> $admission_city,
                "image"=> $admission_image,
                "status" => 0,
                "updatetime" => time(),
            ];
            $res = Attractions::where(["user_id"=>$user_id])->update($data);
            if ($res){
                $this->success("申请成功");
            }else{
                $this->error("申请失败");
            }
        }else{
            $data = [
                "user_id" => $user_id,
                "name" => $name,
                "tel" => $tel,
                "card" => $card,
                "yy_image" => $yy_image,
                "title"=> $admission_title,
                "city"=> $admission_city,
                "image"=> $admission_image,
                "status" => 0,
                "createtime" => time(),
                "updatetime" => time(),
            ];
            $res = Attractions::create($data);
            if ($res){
                $this->success("申请成功");
            }else{
                $this->error("申请失败");
            }
        }
    }

    /*
     * 修改会员个人信息
     */
    public function profile()
    {
        $user = $this->auth->getUser();
        $detail = Detail::where(["user_id"=>$user['id']])->find();
        $username = $this->request->post('username')??"";
        $birthday = $this->request->post('birthday')??"";
        $gender = $this->request->post('gender');
        $avatar = $this->request->post('avatar');
        $back_avatar = $this->request->post('back_avatar');
        $c_city = $this->request->post('c_city')??"";
        if ($username) {
            if ($this->config['content_security_switch']){
                $res = (new Wechat('wxMiniProgram'))->textContentCheck($username,$detail['openid']);
                if($res['errcode']!=0){
                    $this->error("不可使用该昵称");
                }
            }
            $user->username = $username;
        }
        if ($birthday) {
            $user->birthday = $birthday;
        }
        if ($gender) {
            $user->gender = $gender;
        }
        if ($avatar) {
            if ($this->config['content_security_switch']){
                $res = (new Wechat('wxMiniProgram'))->imageContentCheck(cdnurl($avatar,true),$detail['openid']);
                if($res['errcode']!=0){
                    $this->error("不可使用该图片");
                }
            }
            $user->avatar = $avatar;
        }
        if ($back_avatar) {
            if ($this->config['content_security_switch']){
                $res = (new Wechat('wxMiniProgram'))->imageContentCheck(cdnurl($back_avatar,true),$detail['openid']);
                if($res['errcode']!=0){
                    $this->error("不可使用该图片");
                }
            }
            $detail->back_avatar = $back_avatar;
        }
        if ($c_city) {
            $area = Area::whereLike('name',"%$c_city%")->find();
            if (!$area){
                $this->error("城市不存在");
            }
            if ($area['pid']){
                $parea = Area::get($area['pid']);
                $c_city = $parea['name']."/".$area['name'];
            }else{
                $c_city = $area['name'];
            }
            $detail->c_city = $c_city;
        }
        $user->save();
        $detail->save();
        $this->success("修改成功");
    }


    /*
     * 手机号绑定微信账号
     */
    public function binding(){
        $userId = $this->auth->id;
        $code        = $this->request->post('code');
        //查询该用户是否已绑定
        $u = U::get($userId);
        $user = Detail::get(["user_id"=>$userId]);
        if (!empty($user['openid'])){
            $this->error("已绑定，无需重复绑定");
        }
        if (empty($code)) {
            $this->error('code不能为空');
        }
        //微信授权登录
        $login = new Wxlogin('fzly');
        $res   = $login->get_openid($code);
        if (!$res["code"]){
            $this->error($res['msg']);
        }

        $openId = $res['data']['openid'];
        //更新用户表中的openid
        $user->openid = $openId;
        $user->save();

        $this->success("绑定成功",["openid"=>$openId]);
    }

    /*
     * 获取openid
     */
    public function get_openid(){
		$userId = $this->auth->id;
        $code  = $this->request->post('code');
        //查询该用户是否已绑定

        if (empty($code)) {
            $this->error('code不能为空');
        }
        //微信授权登录
        $login = new Wxlogin('fzly');
        $res   = $login->get_openid($code);
        if (!$res["code"]){
            $this->error($res['msg']);
        }
		//更新用户表中的openid
        $user = Detail::get(["user_id"=>$userId]);
        $user->openid = $res['data']['openid'];
        $user->save();

        $this->success("success",["openid"=>$res['data']['openid']]);
    }

    /*
     * 解除微信绑定
     */
    public function remove(){
        $userId = $this->auth->id;
        //查询该用户是否已绑定
        $user = Detail::get(["user_id"=>$userId]);
        if (empty($user['openid'])){
            $this->error("还未绑定微信账号");
        }
        $user->openid = "";
        $user->save();
        $this->success("解绑成功");
    }



    /*
     * 获取协议
     */
    public function pact(){
        $id = $this->request->post("name");
        if (!$id){
            $this->error("缺少参数");
        }
        $res = Pact::get(["title"=>$id]);
        $this->success("成功",$res);

    }

    /*
     * 退出登录
     */
    public function logout()
    {
        if (!$this->request->isPost()) {
            $this->error("请求方法异常");
        }
        $this->auth->logout();
        $this->success("退出登录成功");
    }

    /*
     * 申请成为导游
     */
    public function apply()
    {
        $user_id = $this->auth->id;
        $name = $this->request->post("name");
        $tel = $this->request->post("tel");
        $number = $this->request->post("number");
        $organ = $this->request->post("organ");
//        $bank = $this->request->post("bank");
        $font_image = $this->request->post("font_image");
        $back_image = $this->request->post("back_image");
        $certificate_image = $this->request->post("certificate_image");
        if (!$name){
            $this->error("姓名不能为空");
        }
        if (!$tel) {
            $this->error("手机号不能为空");
        }
        if (!preg_match("/^1[345789]\d{9}$/", $tel)) {
            $this->error("手机号格式不正确");
        }
        if (!$number) {
            $this->error("身份证号不能为空");
        }
        if (!$organ) {
            $this->error("发证机关不能为空");
        }
//        if (!$bank) {
//            $this->error("银行卡号不能为空");
//        }
        if (!$font_image) {
            $this->error("身份证正面照不能为空");
        }
        if (!$back_image) {
            $this->error("身份证背面照不能为空");
        }
        if (!$certificate_image) {
            $this->error("导游证不能为空");
        }
        $user = U::get($user_id);
        if ($user['status']!="normal"){
            $this->error("用户状态不正确");
        }
        $detail = Detail::get(["user_id"=>$user_id]);
        if ($detail['is_dy']){
            $this->error("已是导游，无需重复申请");
        }
        //判断是否已经申请过
        $apply = Examine::where(["user_id"=>$user_id,"status"=>1])->find();
        if ($apply){
            $this->error("已申请过，请耐心等待审核");
        }
        $data = [
            "user_id" => $user_id,
            "name" => $name,
            "tel" => $tel,
            "number" => $number,
            "organ" => $organ,
//            "bank" => $bank,
            "font_image" => $font_image,
            "back_image" => $back_image,
            "certificate_image" => $certificate_image,
            "status" => 1,
            "createtime" => time(),
            "updatetime" => time(),
        ];
        $res = Examine::create($data);
        if ($res){
            $this->success("申请成功");
        }else{
            $this->error("申请失败");
        }
    }

    /*
     * 意见反馈
     */
    public function feedback()
    {
        $user_id = $this->auth->id;
        $content = $this->request->post("content");
        $tel = $this->request->post("tel");
        if (!$content) {
            $this->error("内容不能为空");
        }
        if (!$tel) {
            $this->error("联系方式不能为空");
        }
        if (!preg_match("/^1[3456789]\d{9}$/", $tel)) {
            $this->error("手机号格式不正确");
        }
        $data = [
            "user_id" => $user_id,
            "content" => $content,
            "tel" => $tel,
            "createtime" => time(),
            "updatetime" => time(),
        ];
        $res = Feedback::create($data);
        if ($res) {
            $this->success("反馈成功");
        } else {
            $this->error("反馈失败");
        }
    }

    /*
     * 历史搜索记录
     */
    public function history()
    {
        $user_id = $this->auth->id;
        $history = Hitory::where(["user_id" => $user_id])->select();
        $this->success("成功", $history);
    }

    /*
     * 删除历史搜索记录
     */
    public function delHistory()
    {
        $user_id = $this->auth->id;
        $id = $this->request->post("id")?? "";
        if (!$id) {
            Hitory::destroy(["user_id" => $user_id]);
            $this->success("删除成功");
        }
        $res = Hitory::destroy($id);
        if ($res) {
            $this->success("删除成功");
        } else {
            $this->error("删除失败");
        }
    }

    /*
     * 关注/取消关注
     */
    public function follow()
    {
        $user_id = $this->auth->id;
        $df_id = $this->request->post("df_id")?? "";
        $type = $this->request->post("type")?? 1; //1关注 2取消关注
        if (!$df_id) {
            $this->error("缺少对方id");
        }
        $res = Follow::get(["user_id"=>$user_id,"df_id"=>$df_id]);
        if ($type==1){
            if ($res){
                $this->error("已关注");
            }else{
                $data = [
                    "user_id" => $user_id,
                    "df_id" => $df_id,
                    "createtime" => time(),
                    "updatetime" => time(),
                ];
                $res = Follow::create($data);
                //添加用户关注数
                Detail::where(["user_id" => $user_id])->setInc("gz_s");
                Detail::where(["user_id" => $df_id])->setInc("fs_s");
                if ($res){
                    $this->success("关注成功");
                }else{
                    $this->error("关注失败");
                }
            }
        }else{
            if ($res){
                $res->delete();
                //添加用户关注数
                Detail::where(["user_id" => $df_id])->setDec("fs_s");
                Detail::where(["user_id" => $user_id])->setDec("gz_s");
                $this->success("取消关注成功");
            }else{
                $this->error("未关注");
            }
        }

    }

    /*
     * 新增出行人
     */
    public function add_traveler(){
        $user_id = $this->auth->id;
        $name = $this->request->post("name");
        $tel = $this->request->post("tel");
        $id_card = $this->request->post("id_card");
        if (!$name){
            $this->error("姓名不能为空");
        }
        if (!$tel) {
            $this->error("手机号不能为空");
        }
        if (!preg_match("/^1[3456789]\d{9}$/", $tel)) {
            $this->error("手机号格式不正确");
        }
        if (!$id_card) {
            $this->error("身份证号不能为空");
        }
        $data = [
            "user_id" => $user_id,
            "name" => $name,
            "tel" => $tel,
            "id_card" => $id_card,
            "createtime" => time(),
            "updatetime" => time(),
        ];
        $res = Travel::create($data);
        if ($res){
            $this->success("新增成功");
        }else {
            $this->error("新增失败");
        }

    }

    /*
     * 出行人列表
     */
    public function traveler_list(){
        $user_id = $this->auth->id;
        $traveler = Travel::where(["user_id" => $user_id])->order("createtime desc")->select();
        $this->success("成功", $traveler);
    }

    /*
     * 删除出行人
     */
    public function del_traveler(){
        $user_id = $this->auth->id;
        $id = $this->request->post("id/a") ?? "";
        if (!$id) {
            Travel::destroy(["user_id" => $user_id]);
            $this->success("删除成功");
        }
        $res = Travel::whereIn("id",$id)->delete();
        if ($res) {
            $this->success("删除成功");
        } else {
            $this->error("删除失败");
        }
    }

    /*
     * 修改出行人
     */
    public function edit_traveler(){
        $user_id = $this->auth->id;
        $id = $this->request->post("id");
        $name = $this->request->post("name");
        $tel = $this->request->post("tel");
        $id_card = $this->request->post("id_card");
        if (!$id) {
            $this->error("缺少参数");
        }
        if (!$name){
            $this->error("姓名不能为空");
        }
        if (!$tel) {
            $this->error("手机号不能为空");
        }
        if (!preg_match("/^1[3456789]\d{9}$/", $tel)) {
            $this->error("手机号格式不正确");
        }
        if (!$id_card) {
            $this->error("身份证号不能为空");
        }
        $data = [
            "name" => $name,
            "tel" => $tel,
            "id_card" => $id_card,
            "updatetime" => time(),
        ];
        $res = Travel::update($data,["id" => $id]);
        if ($res){
            $this->success("修改成功");
        }else {
            $this->error("修改失败");
        }
    }

    /*
     * 获取他人信息
     */
    public function other_info(){
        $user_id = $this->auth->id;
        $id = $this->request->post("id");
        if (!$id) {
            $this->error("缺少对方id");
        }
        $user = U::get($id);
        if (!$user){
            $this->error("用户不存在");
        }
        $detail = Detail::get(["user_id" => $id]);
        if (!$detail){
            $this->error("用户信息不存在");
        }
        //关注数
        $follow_num = Follow::where(["user_id" => $id])->count();
        //粉丝数
        $fans_num = Follow::where(["df_id" => $id])->count();
        //是否关注
        $is_follow = Follow::get(["user_id" => $user_id,"df_id" => $id]);
        //获赞数
        $like_num1 = \app\common\model\fzly\content\Content::where(["user_id" => $id])->sum('dz_nums');
        $like_num2 = Trends::where(["user_id" => $id])->sum('dz_nums');
        $like_num = bcadd($like_num1,$like_num2);
        if ($is_follow){
            $is_follow = 1;
        }else{
            $is_follow = 0;
        }
        if ($detail['back_avatar']){
            $detail['back_avatar'] = cdnurl($detail['back_avatar'],true);
        }
        $data = [
            "id" => $user['id'],
            "username" => $user['username'],
            "avatar" => cdnurl($user['avatar'],true),
            "back_avatar" => $detail['back_avatar'],
            "follow_num" => $follow_num,
            "fans_num" => $fans_num,
            "like_num" => intval($like_num),
            "is_follow" => $is_follow,
        ];
        if ($user_id!=$id){
            Detail::where(["user_id" => $id])->setInc("view_s");
        }
        $this->success("成功", $data);

    }

    /*
     * 导游产品详情
     */
    public function guide_info(){
        $id = $this->request->post("id");
        if (!$id) {
            $this->error("缺少产品id");
        }
        $info = Product::get($id);
        if (!$info){
            $this->error("产品不存在");
        }
        $user = U::get($info['user_id']);
        $detail = Detail::get(["user_id" => $info['user_id']]);
		if (!$user){
			$this->error("用户不存在");
        }
        $info['username'] = $user['username'];
        $info['avatar'] = cdnurl($user['avatar'],true);
        $info['month_servers'] = bcadd($detail['month_servers'],\app\common\model\fzly\order\Order::where(["guide_id" => $info['user_id']])->whereIn('status',[2,3])->count());
        $dates = [];
        $today = new \DateTime();
        $weekDays = [
            'Monday'    => '星期一',
            'Tuesday'   => '星期二',
            'Wednesday' => '星期三',
            'Thursday'  => '星期四',
            'Friday'    => '星期五',
            'Saturday'  => '星期六',
            'Sunday'    => '星期日'
        ];
        $now = new DateTime();
        $noon = new DateTime('today 12:00');
        if ($now < $noon) {
            $dates[] = [
                'date' => $today->format('m-d'),
                'weekDay' => $weekDays[$today->format('l')], // 'l' 返回完整的星期名称
            ];
        }


        for ($i = 1; $i <= 7; $i++) {
            // 使用DateInterval添加一天
            $interval = new DateInterval('P1D');
            $today->add($interval);

            // 将日期和星期添加到数组
            $dates[] = [
                'date' => $today->format('m-d'),
                'weekDay' => $weekDays[$today->format('l')],
            ];
        }
        $info['dates'] = $dates;
        $info['image'] = cdnurl($info['image'],true);
        $info['zm_image'] = cdnurl($info['zm_image'],true);
        $info['tg_multiplejson'] = json_decode($info['tg_multiplejson'],true);
        $y = json_decode($info['yd_multiplejson'],true);
        if ($y){
            foreach ($y as $k => &$v){
                if ($v['icon']){
                    $v['icon'] = cdnurl($v['icon'],true);
                }
            }
            $info['yd_multiplejson'] = array_merge($y);
        }


        $info['create_time'] = fzly_format_date($user['createtime']);
        $info['type_name'] = Type::get($info['type_id'])['title'];
        //车型
        $info['model_id'] = explode(",",$info['model']);

        $pro = new Product();
        $data = $pro->getModelList();
        $a = [];
        foreach ($data as $k => $vv){
            if (in_array($k,explode(",",$info['model']))){
                $a[] = $vv;
            }
        }
        $info['model'] = $a;
        $info['duration_id'] = explode(",",$info['duration']);
        $data2 = $pro->getDurationList();
        $arr2 = [];
        foreach ($data2 as $k => $vvv){
            if (in_array($k,explode(",",$info['duration']))){
                $arr2[] = $vvv;
            }
        }
        $info['duration'] = $arr2;
        $info['mobile'] = $user['mobile'];
        Product::where(['id' => $id])->setInc('view_nums');
        $this->success("成功", $info);

    }

    /*
     * 我的收藏喜欢
     */
    public function my_like(){
        $user_id = $this->auth->id;
        $type = $this->request->post("type")?? 1; //1收藏 2喜欢
        $page = $this->request->post("page")?? 1;
        $limit = $this->request->post("limit")?? 10;
        $where = [];
        $where['user_id'] = [ '=', $user_id];
        if ($type == 1){
            $data = Sc::where($where)->whereIn('type',[1,2,3])->paginate(["page"=>$page,"list_rows"=>$limit])->each(function ($item, $key)use ($user_id){
                $content = \app\common\model\fzly\content\Content::get($item['con_id']);
                $item->title = $content['title'];
                $item->image = cdnurl($content['image'],true);
                $user = \app\common\model\User::get($content['user_id']);
                $item->username = $user['username'];
                $item->avatar = cdnurl($user['avatar'],true);
                $item->view_nums = $content['view_nums'];
                $item->dz_nums = $content['dz_nums'];
                $item->sc_nums = $content['sc_nums'];
                $item->is_sc = 1;
                $item->is_dz = 0;
                if (Dz::get(['user_id' => $user_id, 'con_id' => $item['con_id'], 'type' => $content['type']])){
                    $item->is_dz = 1;
                }


            });
        }else{
            $data = Dz::where($where)->whereIn('type',[1,2,3])->paginate(["page"=>$page,"list_rows"=>$limit])->each(function ($item, $key)use ($user_id){
                $content = \app\common\model\fzly\content\Content::get($item['con_id']);
                $item->title = $content['title'];
                $item->image = cdnurl($content['image'],true);
                $user = \app\common\model\User::get($content['user_id']);
                $item->username = $user['username'];
                $item->avatar = cdnurl($user['avatar'],true);
                $item->view_nums = $content['view_nums'];
                $item->dz_nums = $content['dz_nums'];
                $item->sc_nums = $content['sc_nums'];
                $item->is_dz = 1;
                $item->is_sc = 0;
                if (Sc::get(['user_id' => $user_id, 'con_id' => $item['con_id'], 'type' => $content['type']])){
                    $item->is_sc = 1;
                }
            });
        }
        $this->success("成功", $data);

    }

}
