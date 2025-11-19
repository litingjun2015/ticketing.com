<?php

namespace addons\fzly\controller;

use app\common\model\fzly\admission\Admissionmlog;
use app\common\model\fzly\admission\Admissionuser;
use app\common\model\fzly\attractions\Attractions;
use app\common\model\fzly\attractions\Type;
use app\common\model\fzly\user\Detail;
use app\common\controller\Api;
use app\common\model\fzly\user\guide\Product;
use app\common\model\fzly\user\Travel;
use think\Request;

class Admissionm extends Api
{

    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];
    protected $config = [];
    protected $attr_id = '';

    public function __construct(Request $request = null)
    {
        $this->config = get_addon_config("fzly");
        parent::__construct($request);
        $user_id = $this->auth->id;
        //判断该账号是否有景点管理权限
        $user_admission = Admissionuser::get(["user_id"=>$user_id]);
        if (!$user_admission){
            $this->error("该账号没有景点管理权限");
        }
        $this->attr_id = $user_admission['admission_id'];
    }


    /*
     * 门票中心
     */
    public function guide_center(){

        $user_id = $this->auth->id;
        $detail = Detail::get(["user_id" => $user_id]);
        if (!$detail){
            $this->error("用户信息不存在");
        }

        $attractions = Attractions::get($this->attr_id);

        //总收入
        $money = $attractions->money;

        //昨日订单数
        $y_order_num = \app\common\model\fzly\order\Order::where(["attractions_id" => $attractions['id']])
            ->whereIn('status',[2,3])
            ->whereTime('createtime','yesterday')
            ->count();
        //今日订单数
        $t_order_num = \app\common\model\fzly\order\Order::where(["attractions_id" => $attractions['id']])
            ->whereIn('status',[2,3])
            ->whereTime('createtime','d')
            ->count();
        $data = [
            "money" => $money,
            "y_order_num" => $y_order_num,
            "t_order_num" => $t_order_num,
        ];
        //获取总收入
        $total = \app\common\model\fzly\order\Order::where(["attractions_id"=>$attractions['id'],"status"=>3])
            ->sum("order_amount_total");
        $data['total_money'] = $total;
        $this->success("成功", $data);
    }

    /*
     * 订单列表
     */
    public function lists()
    {
        $user_id = $this->auth->id;
        $status = $this->request->post('status') ; //订单状态
        $page = $this->request->post('page') ?? 1;  //页码
        $limit = $this->request->post('limit') ?? 10; //每页显示条数

        $where['attractions_id'] = ['=', $this->attr_id];
        if ($status) {
            $where['status'] = ['=', $status];
        }

        $data = \app\common\model\fzly\order\Order::where($where)->order('id desc')->field('id,order_no,order_type,count,goods_id,status,price,order_amount_total,remark,remark,yd_dsj,yd_time,yd_model,pay_time,createtime')->paginate(["page" => $page, "list_rows" => $limit])
            ->each(function ($item) {
                if ($item->order_type == 1) {
                    $goods = \app\common\model\fzly\admission\Admission::get($item->goods_id);
                } else {
                    $goods = Product::get($item->goods_id);
                }
                $goods['image'] = cdnurl($goods['image'], true);
                if (isset($goods['images']) && $goods['images']){
                    $is = explode(',', $goods['images']);
                    foreach ($is as &$v) {
                        $v = cdnurl($v, true);
                    }
                    $goods['images'] = $is;
                }
                $goods['yd_multiplejson'] = json_decode($goods['yd_multiplejson'], true);
                $item->detail = $goods;
                $item->pays_time = "";
                if ($item->pay_time){
                    $item->pays_time = date('Y-m-d H:i:s', $item->pay_time);
                }
                $item->travel = \app\common\model\fzly\order\Detail::where(['order_id' => $item->id])->select()
                    ->each(function ($ite) {
                        $travel = Travel::get($ite['travel_id']);
                        if ($ite->code_image){
                            $ite->code_image = cdnurl($ite->code_image, true);
                        }
                        $ite->name = $travel['name'];
                        $ite->tel = $travel['tel'];
                        $ite->id = $travel['tel'];
                        $ite->id_card = $travel['id_card'];


                    });
            });
        $this->success("success", ['data' => $data]);
    }

    /*
     * 获取收入明细
     */
    public function income_detail(){
        $user_id = $this->auth->id;
        $page   = $this->request->post('page')??1;  //页码
        $limit  = $this->request->post('limit')??10; //每页显示条数
        $year = $this->request->post('year');
        $month = $this->request->post('month');
        if (!$year){
            $this->error("缺少年份");
        }

        $where = [];
        if ($year){
            $where['y'] = ['=',$year];
        }
        if ($month){
            $where['m'] = ['=',$month];
        }

        $data = Admissionmlog::where(['attractions_id'=>$this->attr_id])->where($where)
            ->order('id desc')
            ->paginate(["page" => $page, "list_rows" => $limit])
            ->each(function ($item){
                $user = \app\common\model\fzly\user\User::get($item['user_id']);
                if ($user){
                    $item['username'] = $user['username'];
                }else{
                    $item['username'] = "";
                }
                $admission = \app\common\model\fzly\admission\Admission::get($item['admission_id']);
                if ($admission){
                    $item['title'] = $admission['title'];
                }else{
                    $item['title'] = "";
                }

            });
        $this->success("成功", $data);
    }

    /*
     * 我的门票产品列表
     */
    public function product_list(){
        $user_id = $this->auth->id;
        $type = $this->request->post("type") ?? 1;   //1=产品 2=已下架
        $page   = $this->request->post('page')??1;   //页码
        $limit  = $this->request->post('limit')??10; //每页显示条数
        $where = [];

        if ($type==1){
            $where['status'] = ['in',['normal']];
        }else{
            $where['status'] = ['=','hidden'];
        }
        $data = \app\common\model\fzly\admission\Admission::where(["attractions_id" => $this->attr_id])->where($where)->order("weigh desc")->paginate(["page"=>$page,"list_rows"=>$limit])
            ->each(function ($item){
                if ($item->image){
                    $item->image = cdnurl($item->image,true);
                }
                if ($item->yd_multiplejson){
                    $item->yd_multiplejson = json_decode($item->yd_multiplejson,true);
                }
                if ($item->mp_multiplejson){
                    $item->mp_multiplejson = json_decode($item->mp_multiplejson,true);
                    $item->price = min(array_column($item->mp_multiplejson,'price'));
                }else{
                    $item->price = 0;
                }
            });
        $this->success("成功", $data);
    }

    /*
     * 门票分类列表
     */
    public function type_list(){
        $data = Type::where(['status' => 'normal'])->order("weigh desc")->select();
        $this->success("成功", $data);
    }

    /*
     * 门票类型列表
     */
    public function type_lists()
    {
        $data = \app\common\model\fzly\admission\Type::where(['status' => 'normal'])->order("weigh desc")->select();
        $this->success("成功", $data);
    }

    /*
     * 发布产品/编辑产品
     */
    public function add_product(){
        $user_id = $this->auth->id;

        $id = $this->request->post("id");//门票id
//        $type_id = $this->request->post("type_id");//门票分类
//        $type_ids = $this->request->post("type_ids/a");//门票类型
//        $title = $this->request->post("title");//门票名称
//        $flag = $this->request->post("flag");//标志:hot=热门,recommend=推荐
//        $city = $this->request->post("city");//城市
//        $image = $this->request->post("image");//缩略图
//        $images = $this->request->post("images/a");//详情图
//        $tags = $this->request->post("tags/a");//标签
//        $address = $this->request->post("address");//地址
//        $address_xx = $this->request->post("address_xx");//详细地址
//        $lon = $this->request->post("lon");//经度
//        $lat = $this->request->post("lat");//纬度
//        $content = $this->request->post("content");//简介
//        $desc = $this->request->post("desc");//简述
//        $guarantee = $this->request->post("guarantee");//保障
//        $open_times = $this->request->post("open_times");//开启时间
//        $end_times = $this->request->post("end_times");//关闭时间
//        $yd_multiplejson = $this->request->post("yd_multiplejson/a");//预定:icon=图标,intro=介绍
        $mp_multiplejson = $this->request->post("mp_multiplejson/a","",null);//门票:intro=名称,label=标签,text=简述,score=来源,price=价格,line_price=价格,ys=已售

//        if (!$type_id){$this->error("请选择门票分类");}
//        if (!$type_ids){$this->error("请选择门票类型");}
//        if ($type_ids){
//            $type_ids = implode(',', $type_ids);
//        }
//        if (!$title){$this->error("请输入门票名称");}
//        if (!$city){$this->error("请输入城市");}
//        if (!$image){$this->error("请上传门票缩略图");}
//        if ($images){
//            $images = implode(',', $images);
//        }
//        if ($tags){
//            $tags = implode(',', $tags);
//        }
//        if (!$address){$this->error("请输入门票地址");}
//        if (!$address_xx){$this->error("请输入门票详细地址");}
//        if (!$lon){$this->error("请输入门票经度");}
//        if (!$lat){$this->error("请输入门票纬度");}
//        if (!$content){$this->error("请输入门票简介");}
//        if (!$desc){$this->error("请输入门票简述");}
//        if (!$guarantee){$this->error("请输入门票保障");}
//        if (!$open_times){$this->error("请输入门票开启时间");}
//        if (!$end_times){$this->error("请输入门票关闭时间");}
//        if (!$yd_multiplejson){$this->error("请输入预定信息");}
        if (!$mp_multiplejson){$this->error("请输入门票信息");}

        $data = [
//            "type_id" => $type_id,
//            "type_ids" => $type_ids,
//            "title" => $title,
//            "flag" => $flag,
//            "city" => $city,
//            "image" => $image,
//            "images" => $images,
//            "tags" => $tags,
//            "address" => $address,
//            "address_xx" => $address_xx,
//            "lon" => $lon,
//            "lat" => $lat,
//            "content" => $content,
//            "desc" => $desc,
//            "guarantee" => $guarantee,
//            "open_times" => $open_times,
//            "end_times" => $end_times,
//            "yd_multiplejson" => json_encode($yd_multiplejson),
            "mp_multiplejson" => json_encode($mp_multiplejson),
//            "attractions_id" => $user_admission['admission_id'],
            "status" => 'hidden',
        ];
        if ($id){
            $data['updatetime'] = time();
            $res = \app\common\model\fzly\admission\Admission::update($data,['id'=>$id]);
            if ($res){
                $this->success("编辑成功");
            }else{
                $this->error("编辑失败");
            }
        }else{
            $data['createtime'] = time();
            $data['updatetime'] = time();
            $res = \app\common\model\fzly\admission\Admission::create($data);
            if ($res){
                $this->success("发布成功");
            }else{
                $this->error("发布失败");
            }
        }
    }



}
