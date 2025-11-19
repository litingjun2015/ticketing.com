<?php

namespace addons\fzly\controller;

use addons\fzly\library\Wechat;
use app\common\model\fzly\distribution\Grade;
use app\common\model\fzly\user\Detail;
use app\common\model\fzly\distribution\Withdraw as Log;
use app\common\model\fzly\distribution\Log as DL;
use app\common\controller\Api;
use app\common\model\fzly\user\User;
use Grafika\Color;
use Grafika\Grafika;
use think\Db;
use think\Exception;
use app\common\model\fzly\distribution\Tghb;


class Distribution extends Api
{

    // 无需登录的接口,*表示全部
    protected $noNeedLogin = ['index'];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['*'];

    /*
     * 代理中心
     */
    public function index(){
        $config = get_addon_config('fzly');
        $userId   = $this->auth->id??0;
        if ($userId){
            $userInfo = User::get($userId);
            $detail = Detail::get(['user_id'=>$userId]);
            //查询代理等级
            $info  = Grade::get($detail->proxy_level);
            //累计佣金
            $withdrawal = DL::where(["user_id"=>$userId,"status"=>2])->sum("distribution_amount");
            $commissionAll = $withdrawal;
            //下线总数 = 一级成员 + 二级成员
            $ids = Detail::where("parent_id",$userInfo->id)->column('user_id');
            $people = User::whereIn("id",$ids)->field("id,username,createtime")->select()->each(function ($item){
                $item->children      = Detail::where("parent_id",$item->id)->field("id,user_id")->select()
                                        ->each(function ($item){
                                            $user = User::get($item->user_id);
                                            if ($user){
                                                $item->username = $user['username'];
                                                $item->createtime = $user['createtime'];
                                            }else{
                                                $item->username = "";
                                                $item->createtime = "";
                                            }

                                        });
                $item->childrenCount = Detail::where("parent_id",$item->id)->count();
            });
            $oneCount = count($people->toArray());//一级人数
            $twoCount = array_sum(array_column($people->toArray(),"childrenCount"));
            $peopleCount = bcadd($oneCount,$twoCount);
            //待入账
            $recorded = \app\common\model\fzly\distribution\Log::where(["user_id"=>$userId,"status"=>1])->sum("distribution_amount");
            //可提现金额
            $commission = $detail->proxy_amound;
            //提现中金额
            $commissioning = $detail->freeze_amound;
            //已提现金额
            $commissionOver = Log::where(["user_id"=>$userId,"status"=>4])->sum("withdraw_money");
            $data = [
                "username"      => $userInfo->username,
                "avatar"        => cdnurl($userInfo->avatar,true),
                "proxy_level"   => $detail->proxy_level,      //代理等级
                "level_title"   => $info['name'],               //等级名称
                "level_icon"    => cdnurl($info['image'],true),               //等级名称
                "commissionAll" => $commissionAll,              //累计佣金
                "peopleCount"   => $peopleCount,                //下线总数
                "commission"    => $commission,                 //可提现金额
                "commissioning" => $commissioning,              //提现中金额
                "commissionOver"=> $commissionOver,             //已提现金额
                "recorded"      => $recorded,                   //待入账
                "min_withdraw"      => $config['min_withdraw'],               //最低提现金额
            ];
        }else{

            $this->error("请登录");

            $data = [
                "username"      => "",
                "avatar"        => "",
                "proxy_level"   => "",      //代理等级
                "level_title"   => "",               //等级名称
                "commissionAll" => 0,              //累计佣金
                "peopleCount"   => 0,                //下线总数
                "commission"    => 0,                 //可提现金额
                "commissioning" => 0,              //提现中金额
                "commissionOver"=> 0,             //已提现金额
                "recorded"      => 0,                   //待入账
            ];
        }

        $this->success("成功",$data);

    }

    /*
     * 提现记录
     */
    public function drawLog(){
        $page   = $this->request->post('page')??1;  //页码
        $limit  = $this->request->post('limit')??10; //每页显示条数
        $userId   = $this->auth->id;
        $status = $this->request->post('status');   // 状态
        $where = [];
        if ($status){
            $where['status'] = array("=",$status);
        }
        $data = Log::where(["user_id"=>$userId])->where($where)->paginate(["page"=>$page,"list_rows"=>$limit])->each(function ($item){
            $user = User::get($item->user_id);
            $item->username = $user['username'];
            $item->createtime = date("Y-m-d H:i",$item->createtime);
        });
        foreach ($data as $row){
            $row->hidden(["user"]);
        }
        $this->success("成功",$data);
    }

    /*
     * 提现申请
     */
    public function draw(){
        $userId   = $this->auth->id;
        $withdraw_money     = $this->request->post('withdraw_money');         // 提现金额
        $type = $this->request->post('type')??'wechat';   // 提现方式
        $bank = $this->request->post('bank');   // 银行卡
        $ali  = $this->request->post('ali');    // 支付宝账号
        if ($withdraw_money<=0){
            $this->error("提现金额不能为零");
        }
        //校验$withdraw_money是否是数字类型
        if (!is_numeric($withdraw_money)){
            $this->error("提现金额必须为数字类型");
        }
        //参数校验
        if (empty($withdraw_money)){
            $this->error("提现金额不能为空");
        }
        if (empty($type)){
            $this->error("提现方式不能为空");
        }
        if ($type == 2 && empty($ali)){
            $this->error("提现方式为支付宝，支付宝账号必填");
        }
        if ($type == 3 && empty($bank)){
            $this->error("提现方式为银行卡，银行卡账号必填");
        }
        //判断当前用户余额是否充足
        $detail  = Detail::get(["user_id"=>$userId]);
        if ($detail['proxy_amound'] < $withdraw_money){
            $this->error("剩余可提现余额不足");
        }
        //判断当前用户是否有提现中的记录
        $log = Log::where(["user_id"=>$userId,"status"=>1])->find();
        if ($log){
            $this->error("您有一笔提现正在审核中，请等待审核结束进行提现");
        }
        //添加提现申请记录 冻结提现佣金
        Db::startTrans();
        try {
            Log::create([
                "user_id"   =>$userId,
                "withdraw_money"=>$withdraw_money,
                "leave_withdraw_money"=>bcsub($detail->proxy_amound,$withdraw_money,2),
                "createtime"=>time(),
                "type"      =>$type,
                "status"    =>1,
                "bank"      =>$bank,
                "ali"       =>$ali,
            ]);
            Detail::update(["proxy_amound"=>bcsub($detail->proxy_amound,$withdraw_money,2),"freeze_amound"=>$withdraw_money],["user_id"=>$userId]);
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        $this->success("提现申请成功，请等待审核");
    }

    /*
     * 我的团队
     */
    public function team(){
        $userId   = $this->auth->id;
        //下线总数 = 一级成员 + 二级成员
        $children = [];
        $p_ids = Detail::where("parent_id",$userId)->column('user_id');
        $people = User::whereIn("id",$p_ids)->field("id,username,createtime")->select()->each(function ($item) use($children){
            $item->childrenCount = Detail::where("parent_id",$item->id)->field("id,username,createtime")->count();
        });
        $people = $people->toArray();
        foreach ($people as $k => $v){
            $p_id = Detail::where("user_id",$v['id'])->column('user_id');
            $children[] = User::whereIn("id",$p_id)->field("id,username,createtime")->select()->toArray();
        }
        $oneCount = count($people);//一级人数
        $twoCount = array_sum(array_column($people,"childrenCount"));//二级人数
        $arr = [];
        foreach ($children as $v){
            foreach ($v as $val){
                $arr[] = $val;
            }
        }
        $ids = array_merge(array_column($people,"id"),array_column($arr,"id"));//团队成员的id
        $ids = implode(',',$ids);
        $peopleCount = bcadd($oneCount,$twoCount);//总人数
        $daystart = strtotime(date("Y-m-d",time()));
        $dayend   = $daystart+60*60*24;
        $beginYesterday=mktime(0,0,0,date('m'),date('d')-1,date('Y'));
        $endYesterday=mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
        $beginThismonth=mktime(0,0,0,date('m'),1,date('Y'));
        $endThismonth=mktime(23,59,59,date('m'),date('t'),date('Y'));
        $lbegin_time = date('Y-m-01 00:00:00',strtotime('-1 month'));
        $lend_time = date("Y-m-d 23:59:59", strtotime(-date('d').'day'));
        $todayCount  = Detail::where("user_id","in",$ids)->where('yqtime', 'between time', [$daystart, $dayend])->count();//今日增加人数
        $ydayCount   = Detail::where("user_id","in",$ids)->where('yqtime', 'between time', [$beginYesterday, $endYesterday])->count();//昨日增加人数
        $onemoonCount = Detail::where("user_id","in",implode(',',array_column($people,"id")))->where('yqtime', 'between time', [$beginThismonth, $endThismonth])->count();//一级本月增加人数
        $twomoonCount = Detail::where("user_id","in",implode(',',array_column($children,"id")))->where('yqtime', 'between time', [$beginThismonth, $endThismonth])->count();//二级本月增加人数
        $onelmoonCount = Detail::where("user_id","in",implode(',',array_column($people,"id")))->where('yqtime', 'between time', [$lbegin_time, $lend_time])->count();//一级上月增加人数
        $twolmoonCount = Detail::where("user_id","in",implode(',',array_column($children,"id")))->where('yqtime', 'between time', [$lbegin_time, $lend_time])->count();//二级上月增加人数
        if ($onemoonCount == 0){
            if ($onelmoonCount == 0){
                $oneb = 0;//一级百分比
            }else{
                @$oneb = '-'.$onelmoonCount*100;//一级百分比
            }
        }else{
            if ($onelmoonCount == 0){
                $oneb = $onemoonCount*100;//一级百分比
            }else{
                @$oneb = round($onelmoonCount/$onemoonCount*100,2);//一级百分比
            }
        }
        if ($twomoonCount == 0){
            if ($twolmoonCount == 0){
                $twob = 0;//一级百分比
            }else{
                @$twob = '-'.$twolmoonCount*100;//二级百分比
            }
        }else{
            if ($twolmoonCount == 0){
                $twob = $twomoonCount*100;//二级百分比
            }else {
                @$twob = round($twolmoonCount / $twomoonCount * 100, 2);//二级百分比
            }
        }
        $data = [
            "peopleCount"=>$peopleCount,//总人数
            "todayCount" =>$todayCount, //今日增加人数
            "ydayCount"  =>$ydayCount,  //昨日增加人数
            "oneCount"   =>$oneCount,   //一级成员人数
            "twoCount"   =>$twoCount,   //二级成员人数
            "onemoonCount"   =>$onemoonCount,   //一级本月增加人数
            "twomoonCount"   =>$twomoonCount,   //二级本月增加人数
            "oneb"        =>$oneb,   //一级百分比
            "twob"        =>$twob,   //二级百分比
        ];
        $this->success("成功",$data);
    }

    /*
     * 我的团队列表
     */
    public function teamlist(){
        $page   = $this->request->post('page')??1;  //页码
        $limit  = $this->request->post('limit')??10; //每页显示条数
        $userId   = $this->auth->id;
        $type     = $this->request->post('type');                           // 1/2 一级或者二级
        $timeOrder      = $this->request->post('timeOrder')??"asc";         // 时间排序
        $peopleOrder    = $this->request->post('peopleOrder')??"asc";       // 人数排序
        $search     = $this->request->post('search');                       // 搜索条件
        //参数校验
        if (empty($type)){
            $this->error("类型不能为空");
        }
        if (!in_array($type,[1,2])){
            $this->error("参数不合法");
        }
        //条件拼接
        $children = [];
        $p_ids = Detail::where("parent_id",$userId)->column('user_id');
        $people = User::whereIn("id",$p_ids)->field("id,username,createtime")->select()->each(function ($item) use($children){
            $item->childrenCount = Detail::where("parent_id",$item->id)->count();
        });
        $people = $people->toArray();
        foreach ($people as $k => $v){
            $p_id = Detail::whereIn("parent_id",$p_ids)->column('user_id');
            $children[] = User::whereIn("id",$p_id)->field("id,username,createtime")->select()->toArray();
        }
        $arr = [];
        foreach ($children as $v){
            foreach ($v as $val){
                $arr[] = $val;
            }
        }

        if ($type == 1){
            $ids = implode(',',array_column($people,"id"));
        }else{
            $ids = implode(',',array_column($arr,"id"));
        }
        $where = [];
        if ($search){
            $where['username|mobile'] = ['like',"%".$search."%"];
        }

        $user = User::where("id","in",$ids)->where($where)
            ->field("id,username,avatar,mobile,createtime")
            ->order('id',$timeOrder)
            ->paginate(["page"=>$page,"list_rows"=>$limit])
            ->each(function ($item) use($children){
                $item->childrenCount = Detail::where("parent_id",$item->id)->count();
                $item->avatar = cdnurl($item->avatar,true);
            });
        $data = $user->toArray();
        if ($peopleOrder == "desc"){
            array_multisort ( array_column( $data['data'] , 'childrenCount' ),SORT_DESC,$data['data']);
        }

        if ($timeOrder == "desc"){
            array_multisort ( array_column( $data['data'] , 'createtime' ),SORT_DESC,$data['data']);
        }
        $this->success("成功",$data);
    }

    /*
     * 佣金明细
     */
    public function detailed(){
        $userId   = $this->auth->id;
        $page   = $this->request->post('page')??1;  //页码
        $limit  = $this->request->post('limit')??10; //每页显示条数
        $time     = $this->request->post('time');         // 时间
        $where = [];
        if ($time){
            $daystart = strtotime($time);
            $dayend   = $daystart+60*60*24;
            $where["log.createtime"] = ["between time",[$daystart,$dayend]];
        }
        //查询佣金表是否有该用户的明细
        $info = \app\common\model\fzly\distribution\Log::with(["orders"])->where("log.user_id",$userId)->where($where)->field("orders.order_no")->paginate(["page"=>$page,"list_rows"=>$limit])
            ->each(function ($item){
                $item->username = User::get($item->orders->user_id)['username'];
            });
        if (!$info){
            $this->error("暂无佣金明细");
        }
        foreach ($info as $row) {
            $row->hidden(['orders',"user_id","createtime","updatetime"]);
        }
        $contAomunt = \app\common\model\fzly\distribution\Log::with(["orders"])->where("log.user_id",$userId)->sum("distribution_amount");
        $this->success("成功",["data"=>$info,"countamount"=>$contAomunt]);

    }

    /*
    * 推广海报
    */
    public function distlist(){
        $list = Tghb::select();
        foreach ($list as $key=>&$val){
              $getPosterName = "/uploads/fzly/hb/".md5('poster_' . $this->auth->id.$val['id']) . '.png';
             if (!file_exists($getPosterName)) {
                  $val['tgimg'] = $this->scwxacode($val);
                }else{
                  $val['tgimg'] = $getPosterName;
                }
        }
        
        $this->success("成功",$list);
  
    }
    
    private function scwxacode($val)
    {
        $config = get_addon_config('fzly');
        $getPosterName =  md5('poster_' . $this->auth->id.$val['id']) . '.png';
        $user = $this->auth->id;
        $path = $this->request->post('path', '');

        if (empty($path)) {
            $path = 'pages/index/index';
        }

        $wechat = new Wechat('wxMiniProgram');
        $content = $wechat->getApp()->app_code->getUnlimit($user, [
            'page' => $path,
            'is_hyaline' => false,
        ]);


        // 生成小程序码到本地
        $appid = $config['appid'];
        $fileName = 'qrcode_' . md5($appid . $user . $path) . '.png';
        $savePath = ROOT_PATH.config('template.tpl_replace_string.__FILE_UPLOAD_PATH__').'public/uploads/fzly/'.'xcxm/';
        if(!file_exists($savePath)){
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir($savePath,0777,true);
        }
        // 保存到文件
        file_put_contents($savePath.$fileName, $content->getBody());
        
        //推广海报

        // 实例化图像编辑器
        $editor = Grafika::createEditor(['Gd']);
        // 打开海报背景图
        $editor->open($backdropImage, "./".$val['img']);
        // 打开小程序码
        $editor->open($qrcodeImage, "./uploads/fzly/xcxm/".$fileName);

        // 重设小程序码宽高
        $qrcodeWidth = 200;
        $editor->resizeExact($qrcodeImage, $qrcodeWidth, $qrcodeWidth);
        // 小程序码添加到背景图
         
        $qrcodeX =  $val['imgx'];
        $qrcodeY = $val['imgy'];
        $editor->blend($backdropImage, $qrcodeImage, 'normal', 1.0, 'bottom-right', $qrcodeX, $qrcodeY);


        // 保存图片
        $editor->save($backdropImage, $this->getPosterPath().$getPosterName);
      
        return request()->domain()."/uploads/fzly/hb/".$getPosterName;
    }


    // 生成圆形用户头像
    private function circular($imgpath, $saveName = ''){
        // echo $imgpath;die;
        $ext = pathinfo($imgpath);
        $srcImg = null;
        switch ($ext['extension']) {
            case 'jpg':
                $srcImg = imagecreatefromjpeg($imgpath);
                break;
            case 'jpeg':
                $srcImg = imagecreatefromjpeg($imgpath);
                break;
            case 'png':
                $srcImg = imagecreatefrompng($imgpath);
                break;
        }
        // 获取图片尺寸
        $w = imagesx($srcImg);
        $h = imagesy($srcImg);
        // 设定图片宽高（正方形）
        $w = $h = min($w, $h);
        $newImg = imagecreatetruecolor($w, $h);
        // 必须
        imagesavealpha($newImg, true);
        // 拾取一个完全透明的颜色,最后一个参数127为全透明
        $bg = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
        imagefill($newImg, 0, 0, $bg);
        $r = $w / 2; //圆半径
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgbColor = imagecolorat($srcImg, $x, $y);
                if (((($x - $r) * ($x - $r) + ($y - $r) * ($y - $r)) < ($r * $r))) {
                    imagesetpixel($newImg, $x, $y, $rgbColor);
                }
            }
        }
        // 输出图片到文件
        imagepng($newImg, $saveName);
        // 释放空间
        imagedestroy($srcImg);
        imagedestroy($newImg);
    }

    /**
     * 海报图文件路径
     * @return string
     */
    private function getPosterPath()
    {
        // 保存路径
        $tempPath = ROOT_PATH.config('template.tpl_replace_string.__FILE_UPLOAD_PATH__').'public/uploads/fzly/hb/';
        !is_dir($tempPath) && mkdir($tempPath, 0755, true);
        return $tempPath ;
    }

    /**
     * 海报图文件名称
     * @return string
     */
    private function getPosterName()
    {
        // echo $this->index;
        return md5('poster_' . $this->auth->id . "_" . rand(0,999)) . '.png';
    }

    /**
     * 海报图url
     * @return string
     */
    private function getPosterUrl()
    {
        return './public/uploads/fzly/hb/'  . $this->getPosterName() . '?t=' . time();
    }

}
