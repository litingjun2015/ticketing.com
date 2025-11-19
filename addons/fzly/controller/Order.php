<?php

namespace addons\fzly\controller;

use addons\epay\library\Service;
use app\common\controller\Api;
use app\common\model\fzly\admission\Admissionmlog;
use app\common\model\fzly\admission\Admissionuser;
use app\common\model\fzly\attractions\Attractions;
use app\common\model\fzly\coupon\Coupon;
use app\common\model\fzly\coupon\Receive;
use app\common\model\fzly\message\Push;
use app\common\model\fzly\order\Detail;
use app\common\model\fzly\order\Evaluate;
use app\common\model\fzly\user\guide\Product;
use app\common\model\fzly\user\Money;
use app\common\model\fzly\user\Travel;
use think\Db;
use think\Exception;
use think\Log;
use think\Queue;
use think\Request;
use Yansongda\Pay\Pay;
use app\common\model\fzly\distribution\Log as Dislog;
use think\Hook;

class Order extends Api
{

    protected $noNeedLogin = ['notifyx'];
    protected $noNeedRight = ['*'];
    protected $config = [];

    public function __construct(Request $request = null)
    {
        $this->config = get_addon_config("fzly");
        parent::__construct($request);
    }

    /*
     * 获取评价标签
     */
    public function get_evaluate_tags(){
        $type = $this->request->post('type') ?? 1;//1=门票,2=导游产品
        $data = \app\common\model\fzly\evaluate\Evaluate::get(['type'=>$type]);
        if (!$data){
            $this->error("请先添加评价标签");
        }
        $data['xx_json'] = json_decode($data['xx_json'],true);
        $data['tag_json'] = json_decode($data['tag_json'],true);
        $this->success("success", $data);
    }


    /*
    * 计算商品价格
    */
    public function before()
    {
        $user_id = $this->auth->id;
        $type = $this->request->post('type') ?? 1;//1=门票,2=导游产品
        $name = $this->request->post('name');//门票规格
        $id = $this->request->post('id');//产品id
        $travel_id = $this->request->post('travel_id/a');//出行人
        $coupon_id = $this->request->post('coupon_id');//优惠券id
        if (empty($travel_id)) {
            $this->error("请选择出行人");
        }
        $count = count($travel_id);
        if ($type == 1) {
            if (!$name) {
                $this->error("缺少门票规格");
            }
        }
        if (!$id) {
            $this->error("缺少产品id");
        }
        $price = 0;
        if ($type == 1) {
            $data = \app\common\model\fzly\admission\Admission::get($id);
            if (!$data) {
                $this->error("门票不存在");
            }
            $mp = json_decode($data['mp_multiplejson'], true);
            foreach ($mp as $k => $v) {
                if ($v['intro'] == $name) {
                    $price = $v['price'];
                    break;
                }
            }
        } else {
            $data = Product::get($id);
            if (!$data) {
                $this->error("导游产品不存在");
            }
            $price = $data['price'];
        }
        $price = bcmul($price, $count, 2);

        if ($coupon_id) {
            $coupon = \app\common\model\fzly\coupon\Coupon::get($coupon_id);
            $receive = Receive::get(["user_id" => $user_id, "coupon_id" => $coupon_id]);
            if (!$coupon) {
                $this->error("优惠券不存在");
            }
            if ($receive) {
                if ($receive['state'] == 1) {
                    $this->error("该优惠券已使用");
                }
                if ($receive['state'] == -1) {
                    $this->error("该优惠券已过期");
                }
            }
            if ($price >= $coupon['with_amount']) {
                $price = bcsub($price, $coupon['used_amount'], 2);
            }
        }
        if ($price <= 0) {
            $price = 0.01;
        }

        $this->success("success", ['price' => $price]);
    }

    /*
     * 创建订单
     */
    public function create()
    {
        $user_id = $this->auth->id;
        $type = $this->request->post('type') ?? 1;//1=门票,2=导游产品
        $name = $this->request->post('name');//门票规格
        $id = $this->request->post('id');//产品id
        $coupon_id = $this->request->post('coupon_id') ?? 0;//优惠券id
        $travel_id = $this->request->post('travel_id/a');//出行人
        $yd_time = $this->request->post('yd_time');//预订日期
        $yd_dsj = $this->request->post('yd_dsj');//预约时长
        $yd_model = $this->request->post('yd_model');//预约车型

        $info = get_addon_info('epay');
        if (empty($info)) {
            $this->error("请先安装微信支付宝整合插件");
        }

        if (empty($travel_id)) {
            $this->error("请选择出行人");
        }
        if (count($travel_id)>10){
            $this->error("最多选择10个出行人");
        }
        $price = 0;
        $guide_id = 0;
        if ($type == 1) {
            if (!$name) {
                $this->error("缺少门票规格");
            }
            $data = \app\common\model\fzly\admission\Admission::get($id);
            if (!$data) {
                $this->error("门票不存在");
            }
            $mp = json_decode($data['mp_multiplejson'], true);
            foreach ($mp as $k => $v) {
                if ($v['intro'] == $name) {
                    $price = $v['price'];
                    break;
                }
            }
            $attractions_id = $data['attractions_id'];
        } else {
            if (!$yd_time) {
                $this->error("请选择预订日期");
            }

//            if (!$yd_dsj) {
//                $this->error("请选择预约时长");
//            }
//            if (!$yd_model) {
//                $this->error("请选择预约车型");
//            }
            $data = Product::get($id);
            if (!$data) {
                $this->error("导游产品不存在");
            }
            //判断是否是自己的导游产品
            if ($user_id == $data['user_id']){
                $this->error("不能购买自己的导游产品");
            }
            $price = $data['price'];
            $guide_id = $data['user_id'];
        }
        $end_price = bcmul($price, count($travel_id), 2);

        if ($coupon_id) {
            $coupon = \app\common\model\fzly\coupon\Coupon::get($coupon_id);
            $receive = Receive::get(["user_id" => $user_id, "coupon_id" => $coupon_id]);
            if (!$coupon) {
                $this->error("优惠券不存在");
            }
            if ($receive) {
                if ($receive['state'] == 1) {
                    $this->error("该优惠券已使用");
                }
                if ($receive['state'] == -1) {
                    $this->error("该优惠券已过期");
                }
            }
            if ($end_price >= $coupon['with_amount']) {
                $end_price = bcsub($end_price, $coupon['used_amount'], 2);
            }
        }
        if ($end_price <= 0) {
            $end_price = 0.01;
        }

        $orderNo = fzly_orderNo();

        $data = [
            'order_no' => $orderNo,
            'order_type' => $type,
            'user_id' => $user_id,
            'goods_id' => $id,
            'travel_ids' => implode(',', $travel_id),
            'coupon_id' => $coupon_id,
            'status' => 1,
            'price' => $price,
            'count' => count($travel_id),
            'order_amount_total' => $end_price,
            'remark' => $name,
            'yd_dsj' => $yd_dsj,
            'yd_time' => $yd_time,
            'yd_model' => $yd_model,
            'guide_id' => $guide_id,
            'attractions_id' => isset($attractions_id)?$attractions_id:0,
            'createtime' => time(),
            'updatetime' => time(),
        ];
        //数据入库
        Db::startTrans();
        try {

            $order = Db::name('fzly_order')->insertGetId($data);

            //插入佣金明细未入账
            $detail = \app\common\model\fzly\user\Detail::get(['user_id'=>$user_id]);

            if ($detail->parent_id != 0){
                //根据上级代理等级抽取佣金
                $grade = \app\common\model\fzly\user\Detail::where("user_id",$detail->parent_id)->value('proxy_level');
                if ($grade){
                    $info  = $this->commission($price,$grade);
                    $this->addCommission($info,$order,$price,$detail,1);
                    //上上级同样抽取佣金
                    $luser = \app\common\model\fzly\user\Detail::get(["user_id"=>$detail->parent_id]);
                    if ($luser && $luser['parent_id']!=0){
                        $grade = \app\common\model\fzly\user\Detail::where("user_id",$luser['parent_id'])->value('proxy_level');
                        if ($grade){
                            $info  = $this->commission($price,$grade);
                            $this->addCommission($info,$order,$price,$luser,2);
                        }

                    }
                }


            }

            foreach ($travel_id as $v) {
                Detail::create([
                    'order_id' => $order,
                    'travel_id' => $v,
                    'createtime' => time(),
                    'updatetime' => time(),
                ]);
            }
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $this->error("数据录入失败：" . $e->getMessage() . "," . $e->getFile() . "," . $e->getLine());
        }

        //订单放入队列 处理超时取消
        $data = ["orderNo" => $orderNo,"type"=>1];
        // Queue::later(bcmul($this->config['timeout'], 60), 'addons\fzly\job\Overtime', $data, 'fzlyOrderQueue');

        $this->success('返回成功', ['orderNo' => $orderNo]);

    }

    /*
     * 发起支付接口
     */
    public function pay()
    {
        $platform = $this->request->post('platform')?$this->request->post('platform'):'miniapp'; //来源
        $info = get_addon_info('epay');
        if (empty($info)) {
            $this->error("请先安装微信支付宝整合插件");
        }

        $order_no = $this->request->post('order_no'); //订单编号
        $openid = $this->request->post('openid'); //openid

        if (!$order_no) {
            $this->error("请输入订单编号");
        }
        if (!$openid) {
            $this->error("请输入openid");
        }
        if ($platform == 'mp-weixin'){
            $platform = 'miniapp';
        }


        //判断订单状态
        $order = \app\common\model\fzly\order\Order::get(['order_no' => $order_no]);
        if (!$order) {
            $this->error("订单不存在");
        }
        if ($order['status'] == 5) {
            $this->error("该订单已取消，请重新下单");
        }
        if (in_array($order['status'], [2, 3, 4])) {
            $this->error("订单状态不正确");
        }

        //调用微信支付
        if ($order['order_type'] == 1) {
            $pay_title = "购买门票";
        } else {
            $pay_title = "预订导游";
        }

        $params = [
            'amount' => $order['order_amount_total'],
            'orderid' => $order['order_no'],
            'type' => "wechat",
            'title' => $pay_title,
            'notifyurl' => request()->domain() . "/addons/fzly/order/notifyx/type/wechat",
            'returnurl' => "",
            'method' => $platform,
            'openid' => $openid,
        ];
//        var_dump($params);die;
        $result = Service::submitOrder($params);
        $this->success('成功', $result);
    }


    /**
     * 支付成功回调
     */
    public function notifyx()
    {
        $type = $this->request->param('type');
        $pay = \addons\epay\library\Service::checkNotify($type);
        if (!$pay) {
            echo '签名错误';
            return;
        }
        $data = $pay->verify();

        //你可以在这里你的业务处理逻辑,比如处理你的订单状态、给会员加余额等等功能
        if ($data['result_code'] == "SUCCESS") {
            $order = \app\common\model\fzly\order\Order::get(["order_no" => $data['out_trade_no']]);
            if($order['status']>1){
                return $pay->success()->send();
            }
            \app\common\model\fzly\order\Order::update(["status" => 2, 'out_trade_no' => $data['transaction_id'], "pay_time" => time()], ["order_no" => $data['out_trade_no']]);
            $detail = Detail::where("order_id", $order['id'])->select();
            foreach ($detail as $k => $v) {
                //生成核销码
                $rand = fzly_generateRandomString(6);
                $url = "code=" . $rand . ",orderNo=" . $data['out_trade_no'];
                $img = '/uploads/fzly/qr/' . time() . rand(0, 999);
                //生成二维码图片
                $errorCorrectionLevel = 'L';    //容错级别
                $matrixPointSize = 5;            //生成图片大小
                $img = md5($img) . '.png';
                $file_path = ROOT_PATH . config('template.tpl_replace_string.__FILE_UPLOAD_PATH__') . 'public/uploads/fzly/' . 'qr/';
                $path = $file_path;
                if (!file_exists($path)) {
                    //检查是否有该文件夹，如果没有就创建，并给予最高权限
                    mkdir($path, 0777, true);
                }
                $filename = $path . $img;
                include_once ROOT_PATH . 'addons/fzly/library/phpqrcode/phpqrcode.php';
                \QRcode::png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
                $QR = $filename;                //已经生成的原始二维码图片文件
                $QR = imagecreatefromstring(file_get_contents($QR));
                //保存图片,销毁图形，释放内存
                if (!file_exists($filename)) {
                    imagepng($QR, $filename);
                    imagedestroy($QR);
                } else {
                    imagedestroy($QR);
                }
                $code = "/uploads/fzly/qr/" . $img;
                $d = Detail::get($v['id']);
                $d->code = $rand;
                $d->code_image = $code;
                $d->save();
            }

            //分销
            $type = 1;
            $no = $data['out_trade_no'];
            $params = ['type'=>$type,'no'=>$no];
            Hook::listen('fzly_distribution',$params);

            //异步推送消息
            $message = Push::get(['message_type' => 1]);
            if (isset($message) && $message['multiplejson']) {
                Log::info("进入支付回调消息推送流程");
                $json = json_decode($message['multiplejson'], true);
                //参数名对应订阅消息详细内容参数 （以下为测试）
                $data = [];
                foreach ($json as $jv) {
                    switch ($jv['title']) {
                        case '1':
                            $data['data'][$jv['keyword']]['value'] = $order['order_type']==1?"门票":"导游产品";
                            break;
                        case '2':
                            $data['data'][$jv['keyword']]['value'] = $order['order_no'];
                            break;
                        case '3':
                            $data['data'][$jv['keyword']]['value'] = $order['order_amount_total'];
                            break;
                        case '4':
                            $data['data'][$jv['keyword']]['value'] = "请在规定时间内使用";
                            break;
                        case '5':
                            $data['data'][$jv['keyword']]['value'] = date('Y-m-d H:i:s', $order['createtime']);
                            break;

                    }
                }
                $data['openid'] = \app\common\model\fzly\user\Detail::where("user_id", $order->user_id)->value("openid");
                $data['masterplate'] = $message->masterplate;
                $data['point'] = 1;//支付成功

                if ($data['openid']){
                    Log::info("推送用户openid:" . $data['openid']);
                    Log::info("推送数据:" . json_encode($data));
                    Log::info("进入支付回调消息推送队列");
                    Queue::later(2, 'addons\fzly\job\SendMessage', $data, 'fzlyOrderQueue');
                }
            }

        } else {
            \app\common\model\fzly\order\Order::update(["status" => 1], ["order_no" => $data['out_trade_no']]);
        }
        //下面这句必须要执行,且在此之前不能有任何输出
        return $pay->success()->send();
    }

    /*
     * 订单列表
     */
    public function lists()
    {
        $user_id = $this->auth->id;
        $status = $this->request->post('status') ?? 1; //订单状态:1=待付款,2=已付款,3=已核销,4=待退款,5=已取消
        $page = $this->request->post('page') ?? 1;  //页码
        $limit = $this->request->post('limit') ?? 10; //每页显示条数

        $where['user_id'] = ['=', $user_id];
        if ($status) {
            $where['status'] = ['=', $status];
        }

        $data = \app\common\model\fzly\order\Order::where($where)->order('id desc')->field('id,order_no,order_type,count,goods_id,status,price,order_amount_total,remark,remark,yd_dsj,yd_time,yd_model,pay_time,createtime,coupon_id')->paginate(["page" => $page, "list_rows" => $limit])
            ->each(function ($item)use ($user_id) {
                if ($item->order_type == 1) {
                    $goods = \app\common\model\fzly\admission\Admission::get($item->goods_id);
                } else {
                    $goods = Product::get($item->goods_id);
                }
                $item->coupon = "";
                if ($item->coupon_id){
                    $coupon = \app\common\model\fzly\coupon\Coupon::get($item->coupon_id);
                    $item->coupon = $coupon;
                }
                $goods['image'] = cdnurl($goods['image'], true);
                if (isset($goods['images']) && $goods['images']){
                    $is = explode(',', $goods['images']);
                    foreach ($is as $k => &$v) {
                        $v = cdnurl($v, true);
                    }
                    $goods['images'] = $is;
                }
                $goods['yd_multiplejson'] = json_decode($goods['yd_multiplejson'], true);
                //获取月销
                $month_sales = \app\common\model\fzly\order\Order::where(['goods_id' => $item->goods_id, 'createtime' => ['>=', strtotime("-1 month")], 'order_type' => $item->order_type])->whereIn('status', [2, 3])->count();
                $goods['month_sales'] = $month_sales;
                $item->detail = $goods;
                $item->pays_time = "";
                if ($item->pay_time){
                    $item->pays_time = date('Y-m-d H:i:s', $item->pay_time);
                }
                $item->travel = Detail::where(['order_id' => $item->id])->select()
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
                $item->is_pl = 0;
                $ev = Evaluate::where(['order_id' => $item->id,"user_id"=>$user_id])->find();
                if ($ev){
                    $item->is_pl = 1;
                }

            });
        $this->success("success", ['data' => $data]);
    }

    /*
     * 订单详情
     */
    public function detail()
    {
        $user_id = $this->auth->id;
        $id = $this->request->post('id');  //订单id
        if (!$id) {
            $this->error("缺少订单id");
        }
        $order = \app\common\model\fzly\order\Order::get(['user_id' => $user_id, 'id' => $id]);
        if (!$order) {
            $this->error("订单不存在");
        }
        $detail = Detail::where(['order_id' => $order['id']])->select()
            ->each(function ($item) {
                $travel = Travel::get($item['travel_id']);
                $item->code_image = cdnurl($item->code_image, true);
                $item->travel_name = $travel['name'];
                $item->travel_mobile = $travel['tel'];
                $item->travel_card = $travel['id_card'];
            });
        $this->success("success", ['order' => $order, 'detail' => $detail]);
    }


    /*
     * 核销二维码
     */
    public function hx_code()
    {
        $order_no = $this->request->post('order_no');
        $code = $this->request->post('code');
        if (!$code) {
            $this->error("缺少核销码");
        }
        $order = \app\common\model\fzly\order\Order::get(['order_no' => $order_no]);
        if (!$order) {
            $this->error("订单不存在");
        }
        $detail = Detail::where(['order_id' => $order['id'], 'code' => $code])->find();
        if (!$detail) {
            $this->error("核销码不存在");
        }
        if ($detail['status'] == 2) {
            $this->error("该核销码已使用");
        }
        if ($detail['status'] == 3) {
            $this->error("该订单已取消");
        }
        $detail->status = 2;
        $detail->save();
        if ($order['order_type']==1){
            $goods = \app\common\model\fzly\admission\Admission::get($order['goods_id']);
        }else{
            $goods = Product::get($order['goods_id']);
        }
        //查询订单是否全部核销
        $detail_count = Detail::where(['order_id' => $order['id'], 'status' => 2])->count();
        if ($detail_count == $order['count']) {
            \app\common\model\fzly\order\Order::update(['is_hx' => 1, 'status' => 3], ['id' => $order['id']]);
            //增加导游收益
            if ($order['order_type'] == 2) {
                $guide = \app\admin\model\User::get($order['guide_id']);
                $guide_money = bcadd($guide['money'], $order['order_amount_total'], 2);
                Money::create([
                    "gudie_id"=>$order['guide_id'],
                    "user_id"=>$order['user_id'],
                    "money"=>$order['order_amount_total'],
                    "before"=>$guide['money'],
                    "after"=>$guide_money,
                    "y"=>date("Y"),
                    "m"=>date("m"),
                    "d"=>date("d"),
                    "createtime"=>time(),
                ]);
                $guide->money = $guide_money;
                $guide->save();
            }
        }
        //异步推送消息
        $message = Push::get(['message_type' => 2]);
        if (isset($message) && $message['multiplejson']) {
            Log::info("进入核销消息推送流程");
            $json = json_decode($message['multiplejson'], true);
            //参数名对应订阅消息详细内容参数 （以下为测试）
            $data = [];
            foreach ($json as $jv) {
                switch ($jv['title']) {
                    case '1':
                        $data['data'][$jv['keyword']]['value'] = $order_no;
                        break;
                    case '6':
                        $data['data'][$jv['keyword']]['value'] = $goods['title'];
                        break;
                    case '7':
                        $data['data'][$jv['keyword']]['value'] = 1;
                        break;
                    case '8':
                        $data['data'][$jv['keyword']]['value'] = date('Y-m-d H:i:s', $order['createtime']);
                        break;
                    case '9':
                        $data['data'][$jv['keyword']]['value'] = "您的订单已被核销";
                        break;

                }
            }
            $data['openid'] = \app\common\model\fzly\user\Detail::where("user_id", $order->user_id)->value("openid");
            $data['masterplate'] = $message->masterplate;
            $data['point'] = 2;//核销成功

            if ($data['openid']){
                Log::info("推送用户openid:" . $data['openid']);
                Log::info("推送数据:" . json_encode($data));
                Log::info("进入支付回调消息推送队列");
                Queue::later(2, 'addons\fzly\job\SendMessage', $data, 'fzlyOrderQueue');
            }
        }
        $this->success("核销成功");
    }

    /*
     * 核销二维码门票
     */
    public function hx_mp_code()
    {
        $user_id = $this->auth->id;
        $order_no = $this->request->post('order_no');
        $code = $this->request->post('code');
        if (!$code) {
            $this->error("缺少核销码");
        }
        //判断该账号是否有门票管理权限
        $user_admission = Admissionuser::get(["user_id"=>$user_id]);
        if (!$user_admission){
            $this->error("该账号没有景点管理权限");
        }
        $order = \app\common\model\fzly\order\Order::get(['order_no' => $order_no]);
        if (!$order) {
            $this->error("订单不存在");
        }
        if ($order['attractions_id']!= $user_admission['admission_id']){
            $this->error("该门票不属于该账号");
        }
        $detail = Detail::where(['order_id' => $order['id'], 'code' => $code])->find();
        if (!$detail) {
            $this->error("核销码不存在");
        }
        if ($detail['status'] == 2) {
            $this->error("该核销码已使用");
        }
        if ($detail['status'] == 3) {
            $this->error("该订单已取消");
        }
        $detail->status = 2;
        $detail->save();
        if ($order['order_type']==1){
            $goods = \app\common\model\fzly\admission\Admission::get($order['goods_id']);
        }else{
            $goods = Product::get($order['goods_id']);
        }
        //查询订单是否全部核销
        $detail_count = Detail::where(['order_id' => $order['id'], 'status' => 2])->count();
        if ($detail_count == $order['count']) {
            \app\common\model\fzly\order\Order::update(['is_hx' => 1, 'status' => 3], ['id' => $order['id']]);
            //增加门票景点收益
            if ($order['order_type'] == 1) {
                $guide = Attractions::get($order['attractions_id']);
                $guide_money = bcadd($guide['money'], $order['order_amount_total'], 2);
                Admissionmlog::create([
                    "attractions_id"=>$order['attractions_id'],
                    "admission_id"=>$order['goods_id'],
                    "user_id"=>$order['user_id'],
                    "money"=>$order['order_amount_total'],
                    "before"=>$guide['money'],
                    "after"=>$guide_money,
                    "y"=>date("Y"),
                    "m"=>date("m"),
                    "d"=>date("d"),
                    "createtime"=>time(),
                ]);
                $guide->money = $guide_money;
                $guide->save();
            }
        }
        //异步推送消息
        $message = Push::get(['message_type' => 2]);
        if (isset($message) && $message['multiplejson']) {
            Log::info("进入核销消息推送流程");
            $json = json_decode($message['multiplejson'], true);
            //参数名对应订阅消息详细内容参数 （以下为测试）
            $data = [];
            foreach ($json as $jv) {
                switch ($jv['title']) {
                    case '1':
                        $data['data'][$jv['keyword']]['value'] = $order_no;
                        break;
                    case '6':
                        $data['data'][$jv['keyword']]['value'] = $goods['title'];
                        break;
                    case '7':
                        $data['data'][$jv['keyword']]['value'] = 1;
                        break;
                    case '8':
                        $data['data'][$jv['keyword']]['value'] = date('Y-m-d H:i:s', $order['createtime']);
                        break;
                    case '9':
                        $data['data'][$jv['keyword']]['value'] = "您的订单已被核销";
                        break;

                }
            }
            $data['openid'] = \app\common\model\fzly\user\Detail::where("user_id", $order->user_id)->value("openid");
            $data['masterplate'] = $message->masterplate;
            $data['point'] = 2;//核销成功

            if ($data['openid']){
                Log::info("推送用户openid:" . $data['openid']);
                Log::info("推送数据:" . json_encode($data));
                Log::info("进入支付回调消息推送队列");
                Queue::later(2, 'addons\fzly\job\SendMessage', $data, 'fzlyOrderQueue');
            }
        }
        $this->success("核销成功");
    }

    /*
     * 订单取消
     */
    public function cancel()
    {
        $user_id = $this->auth->id;
        $id = $this->request->post('id');  //订单id
        if (!$id) {
            $this->error("缺少订单id");
        }
        $order = \app\common\model\fzly\order\Order::get(['user_id' => $user_id, 'id' => $id]);
        if (!$order) {
            $this->error("订单不存在");
        }
        if ($order['status'] == 5) {
            $this->error("订单已取消");
        }
        if ($order['status'] == 4) {
            $this->error("订单已退款");
        }
        if ($order['status'] == 3) {
            $this->error("订单已核销");
        }
        if ($order['status'] == 2) {
            $this->error("订单已支付，请联系客服");
        }
        $order->status = 5;
        $order->save();
        $detail = Detail::where(['order_id' => $order['id']])->select();
        foreach ($detail as $k => $v) {
            $v->status = 3;
            $v->save();
        }
        $this->success("订单取消成功");
    }

    /*
     * 订单评论
     */
    public function comment()
    {
        $user_id = $this->auth->id;
        $id = $this->request->post('id'); //订单id
        $xx_json = $this->request->post('xx_json/a'); //打星标签
        $tag_json = $this->request->post('tag_json/a'); //评论标签
        $evaluate = $this->request->post('evaluate'); //内容
        $images = $this->request->post('images/a'); //详情图
        $is_nm = $this->request->post('is_nm')??0; //是否匿名
        if (!$id) {
            $this->error("缺少订单id");
        }
        if (!$xx_json){
            $this->error("请打星");
        }
        if (!$tag_json){
            $this->error("请选择标签");
        }
        $order = \app\common\model\fzly\order\Order::get(['user_id' => $user_id, 'id' => $id]);
        if (!$order) {
            $this->error("订单不存在");
        }
        if ($order['status'] == 5) {
            $this->error("订单已取消");
        }
        if ($order['status'] == 1) {
            $this->error("订单未支付，无法评论");
        }
        $comment = Evaluate::where(['order_id' => $order['id'], 'user_id' => $user_id])->find();
        if ($comment) {
            $this->error("您已评论过该订单");
        }

        if (!$evaluate){
            $this->error("请填写评价内容");
        }
        if ($images){
            $images = implode(",",$images);
        }
        if (empty($xx_json)){
            $score = 0;
        }else{
            $columnSum = 0;
            foreach ($xx_json as $k => $v){
                $columnSum += $v['score'];
            }
            $score = bcdiv($columnSum,count($xx_json),1);
        }
        $data = [
            'order_id' => $order['id'],
            'order_type' => $order['order_type'],
            'goods_id' => $order['goods_id'],
            'guide_id' => $order['guide_id'],
            'user_id' => $user_id,
            'score' => $score,
            'evaluate' => $evaluate,
            'images' => $images,
            'is_nm' => $is_nm,
            'xx_json' => json_encode($xx_json),
            'tag_json' => json_encode($tag_json),
            'createtime' => time(),
            'updatetime' => time(),
        ];
        $result = Evaluate::create($data);
        if ($result) {
            if ($order['order_type']==1){
                \app\common\model\fzly\admission\Admission::where(['id' => $order['goods_id']])->setInc('common_nums');
            }
            $this->success("评价成功");
        } else {
            $this->error("评价失败");
        }
    }

    /*
     * 订单退款
     */
    public function refund()
    {
        $user_id = $this->auth->id;
        $id = $this->request->post('id'); //订单id

        $order = \app\common\model\fzly\order\Order::get(['user_id' => $user_id, 'id' => $id]);
        if (!$order) {
            $this->error("订单不存在");
        }
        if ($order['status'] == 5) {
            $this->error("订单已取消");
        }
        if ($order['status'] == 3) {
            $this->error("订单已核销");
        }
        if ($order['status'] == 1) {
            $this->error("订单未支付，无法退款");
        }
        $order->status = 4;
        $order->save();
//        try {
//            //生成退款单号 查询订单
//            $refundNo = "TK" . fzly_orderNo();
//            $orderd['transaction_id'] = $order['out_trade_no'];
//            $orderd['out_refund_no'] = $refundNo;
//            $orderd['total_fee'] = bcmul($order['order_amount_total'], 100, 0); // 原支付交易的订单总金额
//            $orderd['refund_fee'] = bcmul($order['order_amount_total'], 100, 0); // 退款金额
//            $orderd['notify_url'] = "";
//            $config = Service::getConfig();
//            $pay = Pay::wechat($config);
//            $result = $pay->refund($orderd);
//            Log::info("退款结果:".json_encode($result));
//            if ($result['return_code'] == "SUCCESS") {
//                if ($order->coupon_id > 0){
//                    //更新优惠券为未使用，判断优惠券是否过期
//                    $coupon = Coupon::get($order->coupon_id);
//                    if (time() > $coupon->end_time){
//                        $res5 = Receive::update(['state'=>-1],["user_id"=>$order['user_id'],"coupon_id"=>$order->coupon_id]);
//                    }else{
//                        $res5 = Receive::update(['state'=>0],["user_id"=>$order['user_id'],"coupon_id"=>$order->coupon_id]);
//                    }
//                    Log::log("优惠券更新状态".$res5);
//                }
//                //如果有分销
//                $distribution = Dislog::where(['order_id' => $order['id']])->select();
//                if ($distribution){
//                    foreach ($distribution as $k => $v){
//                        //减少佣金
//                        $userDetail = \app\common\model\fzly\user\Detail::where(['user_id' => $v['user_id']])->find();
//                        $userDetail->proxy_amound = bcsub($userDetail->proxy_amound,$v['distribution_amount'],2);
//                        $userDetail->save();
//                        //删除分销记录
//                        Dislog::destroy($v['id']);
//
//                    }
//                }
//                $r = \app\common\model\fzly\order\Order::update(['status' => 5,'out_refund_no'=>$refundNo,'refund_id'=>$result['refund_id'],"updatetime"=>time()], ['id' => $order['id']]);
//            } else {
//                $r = \app\common\model\fzly\order\Order::update(['status' => 4], ['id' => $order['id']]);
//                $this->error("退款失败:". $result['return_msg']);
//            }
//        } catch (\Exception $e) {
//            $this->error($e->getMessage());
//        }
        $this->success("退款申请成功，请等待管理员审核");
    }

    //佣金明细
    private function commission($amount,$grade){
        $info  = \app\common\model\fzly\distribution\Grade::get($grade);
        if ($info){
            return [
                "one_amount"=>bcmul($amount,bcdiv($info['one_level'],100,2),2),
                "two_amount"=>bcmul($amount,bcdiv($info['two_level'],100,2),2),
            ];
        }else{
            return [
                "one_amount"=>0,
                "two_amount"=>0,
            ];
        }


    }

    //增加佣金明细
    private function addCommission($info,$orderId,$pay_amount,$userInfo,$flag=1){
        Db::startTrans();
        try {
            $pid = $userInfo->parent_id;
            if ($flag == 1){
                $amound = bcadd($info['one_amount'],0,2);
            }else{
                $amound = bcadd($info['two_amount'],0,2);
            }
            Dislog::create([
                "user_id"       =>  $pid,
                "pay_user_id"   =>  $this->auth->id,
                "order_id"      =>  $orderId,
                "pay_amount"    =>  $pay_amount,
                "distribution_amount"  =>   $amound,
                "createtime"    =>time(),
            ]);
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
        }
    }
}
