<?php

namespace addons\fzly\controller;

use app\common\controller\Api;
use app\common\model\fzly\coupon\Receive;
use app\common\model\fzly\user\guide\Product;
use think\Request;

class Coupon extends Api
{
    // 无需登录的接口,*表示全部
    protected $noNeedLogin = ['coupon_list'];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['*'];

    protected $config = [];

    public function __construct(Request $request = null)
    {
        $this->config = get_addon_config("fzly");
        parent::__construct($request);
    }

    /*
     * 优惠券列表
     */
    public function coupon_list(){
        $user_id = $this->auth->id??0;
        $status   = $this->request->post('status')??1;  //状态 1=可领取 -1=不可领取
        $page   = $this->request->post('page')??1;  //页码
        $limit  = $this->request->post('limit')??10; //每页显示条数

        $data = \app\common\model\fzly\coupon\Coupon::where('status','normal')

            ->order("weigh","desc")
            ->field("id,title,image,with_amount,used_amount,quota,start_time,end_time,draw_range,remarks")
            ->select()
            ->each(function ($item) use($user_id){
                //查询剩余可领优惠券数量
                $count = Receive::where("coupon_id",$item->id)->count();
                $item->quota = bcsub($item->quota,$count);
                if ($item->quota<=0){
                    $item->quota = 0;
                }

                $item->status = 1;
                if (Receive::get(["user_id"=>$user_id,"coupon_id"=>$item->id])){
                    $item->status = 0;
                }
                //判断当前时间是否在优惠券领取时间区间
                $draw = explode(' - ',$item->draw_range);
                if (time()<strtotime($draw[0]." 00:00:00") || time()>strtotime($draw[1]." 23:59:59")){
                    $item->status = -1;
                }
                if ($item->image){
                    $item->image = cdnurl($item->image,true);
                }
            });
        $data = $data->toArray();
        if (empty($data)){
            $this->success('返回成功',[]);
        }
        //先获取可领取和不可领取的总条数
        $count1 = $count2 = 0;
        $a1 = $a2 = [];
        foreach ($data as $k => $v){
            if ($v['status'] == 1){
                $count1 += 1;
                $a1[] = $v;
            }else{
                $count2 += 1;
                $a2[] = $v;
            }
        }
        if ($status==1){
            //对a1进行分页
            $data = array_slice($a1,($page-1)*$limit,$limit);

        }else{
            //对a2进行分页
            $data = array_slice($a2,($page-1)*$limit,$limit);
        }
        $this->success('返回成功',["data"=>array_merge($data),"total"=>count($a1),"current_page"=>$page,"per_page"=>$limit]);
    }

    /*
     * 领取优惠券
     */
    public function draw(){
        $user_id = $this->auth->id;
        $id   = $this->request->post('id');     //优惠券id
        if (!$id){
            $this->error("请输入优惠券id");
        }
        $coupon = \app\common\model\fzly\coupon\Coupon::get($id);
        //判断是否在优惠券领取时间区间
        $draw = explode(' - ',$coupon->draw_range);
        if (time()>=strtotime($draw[0]." 00:00:00") && time()<=strtotime($draw[1]." 23:59:59")){
            //判断是否已领取
            $res = Receive::get(["user_id"=>$user_id,"coupon_id"=>$coupon->id]);
            if ($res){
                $this->error("您已领取过该优惠券");
            }
            //领取优惠券
            Receive::create([
                "user_id"       =>  $user_id,
                "coupon_id"     =>  $coupon->id,
                "money"         =>  $coupon->used_amount,
                "create_time"   =>  time(),
                "state"         =>  0,
            ]);
            $this->success('领取成功');
        }else{
            $this->error("该优惠券已过可领取时间");
        }

    }

    /*
     * 获取可用优惠券
     */
    public function usable(){
        $user_id = $this->auth->id;
        $post = $this->request->post();     //商品信息
        $type = $post['type'];  //1=门票 2=导游产品
        $intro = $post['intro'];  //门票名称
        $id = $post['id'];
        $count = $post['count'];
        if (!$type){
            $this->error("缺少类型");
        }
        if (!$id){
            $this->error("缺少产品id");
        }
        if (!$count){
            $this->error("缺少产品数量");
        }

        //查询优惠券领取记录表
        $data = Receive
            ::with(['coupon'])
            ->where(['receive.state'=>'0','user_id'=>$user_id])
            ->select();

        $price = 0;
        if ($type == 1){
            if(!$intro){
                $this->error("缺少门票名称");
            }
            $admission = \app\common\model\fzly\admission\Admission::get($id);
            $mp = json_decode($admission->mp_multiplejson,true);
            foreach ($mp as $k => $v){
                if ($v['intro']==$intro){
                    $price = bcmul($v['price'],$count,2);
                }
            }
        }else{
            $product = Product::get($id);
            $price = bcmul($product['price'],$count,2);
        }
        $arr = [];
        foreach ($data as $row) {
            //判断优惠券是否过期，更新优惠券状态
            $coupon = \app\common\model\fzly\coupon\Coupon::get($row->coupon_id);
            if (time() > $coupon->end_time){
                $row->state = -1;
                Receive::update(["state"=>-1],["id"=>$row->id]);
            }
            $row->getRelation('coupon')->visible(['title','image','start_time','draw_range','end_time','with_amount','used_amount','remarks']);
            if ($row->coupon->with_amount <= $price && time() < $coupon->end_time){
                $arr[] = $row;
            }
        }


        $this->success('返回成功',$arr);

    }

    /*
     * 我的优惠券列表
     */
    public function list(){
        $user_id = $this->auth->id;

        //查询优惠券领取记录表
        $data = Receive
            ::with(['coupon'])
            ->where(['user_id'=>$user_id])
            ->select();

        foreach ($data as $row) {
            //判断优惠券是否过期，更新优惠券状态
            $coupon = \app\common\model\fzly\coupon\Coupon::get($row->coupon_id);
            if (time() > $coupon->end_time){
                $row->state = -1;
                Receive::update(["state"=>-1],["id"=>$row->id]);
            }
            $row->draw_range = $row->coupon->draw_range;
            $row->coupon_title = $row->coupon->title;
            $row->coupon_start_time = $row->coupon->start_time;
            $row->coupon_end_time = $row->coupon->end_time;
            $row->coupon_with_amount = $row->coupon->with_amount;
            $row->coupon_used_amount = $row->coupon->used_amount;
            $row->coupon_remarks = $row->coupon->remarks;
            $row->hidden(['coupon']);
        }

        $this->success('返回成功',$data);


    }
}
