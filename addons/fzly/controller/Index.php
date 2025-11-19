<?php

namespace addons\fzly\controller;

use app\common\controller\Api;
use app\common\model\fzly\advertising\Advertising;
use app\common\model\fzly\advertising\Log;
use app\common\model\fzly\app\Navigation;
use app\common\model\fzly\app\Url;
use app\common\model\fzly\content\Content;
use app\common\model\fzly\order\Evaluate;
use app\common\model\fzly\order\Order;
use app\common\model\fzly\user\Detail;
use app\common\model\fzly\user\guide\Product;
use app\common\model\fzly\user\guide\Type;
use app\common\model\fzly\user\Sc;
use think\Request;

class Index extends Api
{

    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];
    protected $config = [];

    public function __construct(Request $request = null)
    {
        $this->config = get_addon_config("fzly");
        parent::__construct($request);
    }

    /*
     * 广告位列表
     */
    public function ad_list(){
        $url = $this->request->post('url')??'/pages/index/index';
        $position = $this->request->post('position')??1;
        $url = Url::where(['url'=>$url])->find();
        if(!$url){
            $this->error('页面地址不存在');
        }
        $data = Advertising::where(['url_id'=>$url['id'],'position'=>$position,'status'=>"normal"])->whereTime('enddate','>',time())->order('id desc')->field('id,image,tz_url,enddate,params')->select()
                ->each(function($item){
                    $item->image = cdnurl($item->image,true);
                });
        $this->success("success",$data);
    }

    /*
     * 点击广告位记录次数
     */
    public function ad_click()
    {
        $user_id = $this->auth->id??0;
        $id = $this->request->post('id');
        if (empty($id)){
            $this->error("缺少广告位id");
        }
        $ad = Advertising::get($id);
        if (empty($ad)){
            $this->error("广告位不存在");
        }
        $log = Log::where(['user_id'=>$user_id,'advertising_id'=>$id])->find();
        if (empty($log)){
            Log::create(['user_id'=>$user_id,'advertising_id'=>$id,'counts'=>1]);
        }else{
            $log->counts += 1;
            $log->updatetime = time();
            $log->save();
        }
        $this->success('点击成功');
    }

    /*
     * 导航栏
     */
    public function nav_list(){
        $data = Navigation::where(['status'=>"normal"])->order('weigh desc')->field('id,title,url,image,params,weigh,status')->select()
                ->each(function($item){
                    $item->image = cdnurl($item->image,true);
                    $item->url = Url::get($item->url)['url'];
                });
        $this->success("success",$data);
    }

    /*
     * 导游列表
     */
    public function guide_list(){
//        $city = $this->request->header('city')??urlencode($this->config['city']);//城市 默认郑州
        $page   = $this->request->post('page')??1;  //页码
        $limit  = $this->request->post('limit')??10; //每页显示条数
        $search  = $this->request->post('search')??""; //搜索条件
//        $city   = urldecode($city);
        $where = [];
        if($search){
            $where['username'] = ['like',"%$search%"];
        }
        $dy_ids = Detail::where("is_dy",1)->column('user_id');
        $data = \app\common\model\fzly\user\User::whereIn("id",$dy_ids)->where($where)->where("status","normal")->order('id desc')->field('id,username,avatar,gender')->paginate(["page"=>$page,"list_rows"=>$limit])
                ->each(function($item){
                    $detail = Detail::where(['user_id'=>$item['id']])->find();
                    $item->avatar = cdnurl($item->avatar,true);
                    $order = Order::where(['guide_id'=>$item->id])->whereIn("status",[2,3,4])->count();
                    $item->month_servers = bcadd($detail->month_servers,$order);
                    $product = Product::where(['user_id'=>$item->id,'status'=>2])->order("price","asc")->limit(1)->find();
                    $item->price = 0;
                    $item->product = '暂无';
                    if($product){
                        $item->price = $product->price;
                        $gu_type = Type::where(['id'=>$product->type_id])->find();
                        $item->product = $gu_type->title;
                    }
                });
        $this->success("success",$data);
    }

    /*
     * 导游详情
     */
    public function guide_detail()
    {
        $id = $this->request->post('id');
        if (!$id) {
            $this->error('导游id不能为空');
        }
        $data = \app\common\model\fzly\user\User::where(['id' => $id])->field('id,username,avatar,gender,mobile,createtime')->find();
        if (!$data) {
            $this->error('导游不存在');
        }
        $data->avatar = cdnurl($data->avatar, true);
        $detail = Detail::where(['user_id' => $id])->find();
        $data->month_servers = $detail->month_servers;
        if ($detail->back_avatar){
            $data->back_avatar = cdnurl($detail->back_avatar,true);
        }else{
            $data->back_avatar = cdnurl('/assets/img/avatar.png',true);
        }
        //好评率
        $h = Evaluate::where(['guide_id'=>$id,'order_type'=>2])->where('score',">",4)->count();
        $hs = Evaluate::where(['guide_id'=>$id,'order_type'=>2])->count();
        if ($hs){
            $data->good_rate = bcmul(bcdiv($h, $hs, 2),100);
        }else{
            $data->good_rate = 0;
        }

        $data['create_time'] = fzly_format_date($data['createtime']);
        $data['product'] = Product::where(['user_id'=>$id,'status'=>2])->order("weigh","desc")->field('yd_multiplejson,id,image,title,price,type_id')->select()
            ->each(function ($item){
                $item->yd_multiplejson = json_decode($item->yd_multiplejson,true);
                $item->image = cdnurl($item->image,true);
                $type = Type::where(['id'=>$item->type_id])->field('id,title')->find();
                $item->type_title = $type->title;
                $orders = Order::where(['goods_id'=>$item->id])->whereIn("status",[2,3])->count();
                $item->order_nums = $orders;
            });
        $is_sc = 0;
        $dz_res = Sc::where(['user_id'=>$this->auth->id,'con_id'=>$data['id'],'type'=>4])->find();
        if ($dz_res){
            $is_sc = 1;
        }
        $data['is_sc'] = $is_sc;
        $order = Order::where(['guide_id'=>$id])->whereIn("status",[2,3,4])->count();
        $data['month_servers'] = bcadd($detail->month_servers,$order);
        $this->success("success", $data);
    }

    /*
     * 景区分类
     */
    public function spot_type_list(){
        $data = \app\common\model\fzly\attractions\Type::where(['status'=>"normal"])->order('weigh desc')->field('id,title,weigh,status')->select();
        $this->success("success",$data);
    }

    /*
     * 景区推荐
     */
    public function spot_tj(){
        $city = $this->request->header('city')??urlencode($this->config['city']);//城市 默认郑州
        $type_id   = $this->request->post('type_id');  //景区分类id
        $city   = urldecode($city);
        if (!$type_id){
            $this->error('景区分类不能为空');
        }
        $data = \app\common\model\fzly\admission\Admission::where(['status'=>"normal"])
            ->where('city','like',"%$city%")
            ->where('type_id',$type_id)->order('hot','desc')->find();
        if ($data){
            $data->image = cdnurl($data->image,true);
            $data['tj_user'] = \app\common\model\fzly\user\User::where(['status'=>'normal'])->field("username,avatar")->limit(5)->select()
                ->each(function ($item){
                    $item->avatar = cdnurl($item->avatar,true);
                });
        }
        $this->success("success",$data);
    }

    /*
     * 游记推荐
     */
    public function travel_tj(){
        $city = $this->request->header('city')??urlencode($this->config['city']);//城市 默认郑州
        $type_id   = $this->request->post('type_id');  //景区分类id
        $city   = urldecode($city);
        if (!$type_id){
            $this->error('景区分类不能为空');
        }
        $data = Content::where(['status'=>3,"flag"=>"recommend"])
            ->where('city','like',"%$city%")
            ->where('jqt_id',$type_id)->limit(6)->field('id,type,user_id,city,title,image,dz_nums')->select()
            ->each(function($item){
                $item->image = cdnurl($item->image,true);
                $user = \app\common\model\fzly\user\User::where(['id'=>$item->user_id])->field('id,username,avatar')->find();
                if ($user){
                    $item->username = $user->username;
                    $item->avatar = cdnurl($user->avatar,true);
                }else{
                    $item->username = '暂无';
                    $item->avatar = cdnurl('/assets/img/avatar.png',true);
                }
                if (strpos($item->city,'/')!==false){
                    $item->city = explode('/',$item->city)[1];
                }
                $is_sc = 0;
                $dz_res = Sc::where(['user_id'=>$this->auth->id,'con_id'=>$item->id,'type'=>$item->type])->find();
                if ($dz_res){
                    $is_sc = 1;
                }
                $item->is_sc = $is_sc;
            });
        $this->success("success",$data);
    }

    /*
     * 必游榜列表
     */
    public function must_list(){
        $city = $this->request->header('city') ?? urlencode($this->config['city']);//城市 默认郑州
        $type_id   = $this->request->post('type_id');  //景区分类id
        $city   = urldecode($city);
        if (!$type_id){
            $this->error('景区分类不能为空');
        }
        $data = \app\common\model\fzly\admission\Admission::where(['status'=>"normal"])
            ->where('city','like',"%$city%")
            ->where('type_id',$type_id)->order('hot','desc')->field("id,title,image,hot")->limit(10)->select()
            ->each(function ($item){
                $item->image = cdnurl($item->image,true);
            });
        $this->success("success",$data);
    }

    /*
     * 热门活动
     */
    public function activity_list(){
        $page   = $this->request->post('page')??1;  //页码
        $limit  = $this->request->post('limit')??10; //每页显示条数
        $search  = $this->request->post('search'); //搜索条件
        $where = [];
        if($search){
            $where['title'] = ['like',"%$search%"];
        }
        $data = Product::where($where)->where("status",2)->order("id desc,weigh desc")->field("id,type_id,title,image,price,view_nums,sc_nums,status")->paginate(["page"=>$page,"list_rows"=>$limit])
            ->each(function ($item){
                $item->image = cdnurl($item->image,true);
                $type = Type::where(['id'=>$item->type_id])->field('id,title')->find();
                $item->type_title = $type->title;


            });
        $this->success("成功", $data);
    }



}
