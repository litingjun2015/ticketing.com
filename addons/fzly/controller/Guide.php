<?php

namespace addons\fzly\controller;

use addons\fzly\library\WxWithdrawal;
use app\common\library\Sms as Smslib;
use app\common\model\fzly\guide\Type;
use app\common\model\fzly\user\Bank;
use app\common\model\fzly\user\Detail;
use app\common\controller\Api;
use app\common\model\fzly\user\guide\Product;
use app\common\model\fzly\user\guide\Withdraw;
use app\common\model\fzly\user\Money;
use app\common\model\fzly\user\Travel;
use fast\Http;
use Imagick;
use think\Log;
use think\Request;

class Guide extends Api
{

    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];
    protected $config = [];

    public function __construct(Request $request = null)
    {
        $this->config = get_addon_config("fzly");
        parent::__construct($request);
    }

    private static $card_type = array(
        'CC' => '信用卡',
        'DC' => '储蓄卡',
    );

    private static $card_icon = array(
        "ICBC" => "/assets/addons/fzly/img/10cc05c9b2e40d59e47d59ace574542a.png",
        "ABC" => "/assets/addons/fzly/img/ff19e9593851e57b2cc2e05457e0d193.png",
        "CCB" => "/assets/addons/fzly/img/4a1bb5a9c1e14927f2a06385c06dfaf2.png",
        "PSBC" => "/assets/addons/fzly/img/39a77a0d47a3ffb08c9f6a6e7c48adf0.png",
        "CMB" => "/assets/addons/fzly/img/f1933e6ff6db57fffa7ce68f3d9bb3b9.png",
        "BOC" => "/assets/addons/fzly/img/d4caac8de977da77e800064d9ffe7974.png",
        "COMM" => "/assets/addons/fzly/img/9758699b2d53a3b5587ef8b78854b164.png",
        "SPDB" => "/assets/addons/fzly/img/ac896ae8561c6b28149d78b6be79aeae.png",
        "GDB" => "/assets/addons/fzly/img/1676e935970ce51d938fdb1b57a8f723.png",
        "CMBC" => "/assets/addons/fzly/img/1b1c4db302c8ec77558e1a7bf9d2e71b.png",
        "SPABANK" => "/assets/addons/fzly/img/0fde08030db8707673813e4755d6c7a3.png",
        "CEB" => "/assets/addons/fzly/img/71880e2c88e9ce7e4999cd7dd482329f.png",
        "CIB" => "/assets/addons/fzly/img/0af9a08a9d069d4ab083f4ab700bc6db.png",
        "CITIC" => "/assets/addons/fzly/img/20f4aab9a679762c78fdbe9ec53034e5.png",
    );

    private static $bank_info = array(
        // 热门银行
        "ICBC" => "中国工商银行",
        "ABC" => "中国农业银行",
        "CCB" => "中国建设银行",
        "PSBC" => "中国邮政储蓄银行",
        "CMB" => "招商银行",
        "BOC" => "中国银行",
        "COMM" => "交通银行",
        "SPDB" => "上海浦东发展银行",
        "GDB" => "广东发展银行",
        "CMBC" => "中国民生银行",
        "SPABANK" => "平安银行",
        "CEB" => "中国光大银行",
        "CIB" => "兴业银行",
        "CITIC" => "中信银行",
        // 其他
        "SRCB" => "深圳农村商业银行",
        "BGB" => "广西北部湾银行",
        "SHRCB" => "上海农村商业银行",
        "BJBANK" => "北京银行",
        "WHCCB" => "威海市商业银行",
        "BOZK" => "周口银行",
        "KORLABANK" => "库尔勒市商业银行",
        "SDEB" => "顺德农商银行",
        "HURCB" => "湖北省农村信用社",
        "WRCB" => "无锡农村商业银行",
        "BOCY" => "朝阳银行",
        "CZBANK" => "浙商银行",
        "HDBANK" => "邯郸银行",
        "BOD" => "东莞银行",
        "ZYCBANK" => "遵义市商业银行",
        "SXCB" => "绍兴银行",
        "GZRCU" => "贵州省农村信用社",
        "ZJKCCB" => "张家口市商业银行",
        "BOJZ" => "锦州银行",
        "BOP" => "平顶山银行",
        "HKB" => "汉口银行",
        "NXRCU" => "宁夏黄河农村商业银行",
        "NYBANK" => "广东南粤银行",
        "GRCB" => "广州农商银行",
        "BOSZ" => "苏州银行",
        "HZCB" => "杭州银行",
        "HSBK" => "衡水银行",
        "HBC" => "湖北银行",
        "JXBANK" => "嘉兴银行",
        "HRXJB" => "华融湘江银行",
        "BODD" => "丹东银行",
        "AYCB" => "安阳银行",
        "EGBANK" => "恒丰银行",
        "CDB" => "国家开发银行",
        "TCRCB" => "江苏太仓农村商业银行",
        "NJCB" => "南京银行",
        "ZZBANK" => "郑州银行",
        "DYCB" => "德阳商业银行",
        "YBCCB" => "宜宾市商业银行",
        "SCRCU" => "四川省农村信用",
        "KLB" => "昆仑银行",
        "LSBANK" => "莱商银行",
        "YDRCB" => "尧都农商行",
        "CCQTGB" => "重庆三峡银行",
        "FDB" => "富滇银行",
        "JSRCU" => "江苏省农村信用联合社",
        "JNBANK" => "济宁银行",
        "JINCHB" => "晋城银行JCBANK",
        "FXCB" => "阜新银行",
        "WHRCB" => "武汉农村商业银行",
        "HBYCBANK" => "湖北银行宜昌分行",
        "TZCB" => "台州银行",
        "TACCB" => "泰安市商业银行",
        "XCYH" => "许昌银行",
        "NXBANK" => "宁夏银行",
        "HSBANK" => "徽商银行",
        "JJBANK" => "九江银行",
        "NHQS" => "农信银清算中心",
        "MTBANK" => "浙江民泰商业银行",
        "LANGFB" => "廊坊银行",
        "ASCB" => "鞍山银行",
        "KSRB" => "昆山农村商业银行",
        "YXCCB" => "玉溪市商业银行",
        "DLB" => "大连银行",
        "DRCBCL" => "东莞农村商业银行",
        "GCB" => "广州银行",
        "NBBANK" => "宁波银行",
        "BOYK" => "营口银行",
        "SXRCCU" => "陕西信合",
        "GLBANK" => "桂林银行",
        "BOQH" => "青海银行",
        "CDRCB" => "成都农商银行",
        "QDCCB" => "青岛银行",
        "HKBEA" => "东亚银行",
        "HBHSBANK" => "湖北银行黄石分行",
        "WZCB" => "温州银行",
        "TRCB" => "天津农商银行",
        "QLBANK" => "齐鲁银行",
        "GDRCC" => "广东省农村信用社联合社",
        "ZJTLCB" => "浙江泰隆商业银行",
        "GZB" => "赣州银行",
        "GYCB" => "贵阳市商业银行",
        "CQBANK" => "重庆银行",
        "DAQINGB" => "龙江银行",
        "CGNB" => "南充市商业银行",
        "SCCB" => "三门峡银行",
        "CSRCB" => "常熟农村商业银行",
        "SHBANK" => "上海银行",
        "JLBANK" => "吉林银行",
        "CZRCB" => "常州农村信用联社",
        "BANKWF" => "潍坊银行",
        "ZRCBANK" => "张家港农村商业银行",
        "FJHXBC" => "福建海峡银行",
        "FJNX" => "福建省农村信用社联合社",
        "ZJNX" => "浙江省农村信用社联合社",
        "LZYH" => "兰州银行",
        "JSB" => "晋商银行",
        "BOHAIB" => "渤海银行",
        "CZCB" => "浙江稠州商业银行",
        "YQCCB" => "阳泉银行",
        "SJBANK" => "盛京银行",
        "XABANK" => "西安银行",
        "BSB" => "包商银行",
        "JSBANK" => "江苏银行",
        "FSCB" => "抚顺银行",
        "HNRCU" => "河南省农村信用",
        "XTB" => "邢台银行",
        "HXBANK" => "华夏银行",
        "HNRCC" => "湖南省农村信用社",
        "DYCCB" => "东营市商业银行",
        "ORBANK" => "鄂尔多斯银行",
        "BJRCB" => "北京农村商业银行",
        "XYBANK" => "信阳银行",
        "ZGCCB" => "自贡市商业银行",
        "CDCB" => "成都银行",
        "HANABANK" => "韩亚银行",
        "LYBANK" => "洛阳银行",
        "ZBCB" => "齐商银行",
        "CBKF" => "开封市商业银行",
        "H3CB" => "内蒙古银行",
        "CRCBANK" => "重庆农村商业银行",
        "SZSBK" => "石嘴山银行",
        "DZBANK" => "德州银行",
        "SRBANK" => "上饶银行",
        "LSCCB" => "乐山市商业银行",
        "JXRCU" => "江西省农村信用",
        "JZBANK" => "晋中市商业银行",
        "HZCCB" => "湖州市商业银行",
        "NHB" => "南海农村信用联社",
        "XXBANK" => "新乡银行",
        "JRCB" => "江苏江阴农村商业银行",
        "YNRCC" => "云南省农村信用社",
        "GXRCU" => "广西省农村信用",
        "BZMD" => "驻马店银行",
        "ARCU" => "安徽省农村信用社",
        "GSRCU" => "甘肃省农村信用",
        "LYCB" => "辽阳市商业银行",
        "JLRCU" => "吉林农信",
        "URMQCCB" => "乌鲁木齐市商业银行",
        "XLBANK" => "中山小榄村镇银行",
        "CSCB" => "长沙银行",
        "JHBANK" => "金华银行",
        "BHB" => "河北银行",
        "NBYZ" => "鄞州银行",
        "LSBC" => "临商银行",
        "BOCD" => "承德银行",
        "SDRCU" => "山东农信",
        "NCB" => "南昌银行",
        "TCCB" => "天津银行",
        "WJRCB" => "吴江农商银行",
        "CBBQS" => "城市商业银行资金清算中心",
        "HBRCU" => "河北省农村信用社",
        // 特写, 有个官方公会签约主播留的是『上虞农商银行』,支付宝接口返回的是 ZJNX
        "ZJNX_SY" => "上虞农商银行",
    );

    /*
     * 导游中心
     */
    public function guide_center(){
        $user_id = $this->auth->id;
        $detail = Detail::get(["user_id" => $user_id]);
        if (!$detail){
            $this->error("用户信息不存在");
        }
        if (!$detail['is_dy']){
            $this->error("不是导游，不能访问");
        }
        //总收入
        $user = \app\admin\model\User::get($user_id);
        $money = $user->money;
        $with = Withdraw::get(["user_id" => $user_id, "status" => 1]);
        if ($with){
            $money = $money - $with['withdraw_money'];
        }
        //昨日订单数
        $y_order_num = \app\common\model\fzly\order\Order::where(["guide_id" => $user_id])->whereIn('status',[2,3])->whereTime('createtime','yesterday')->count();
        //今日订单数
        $t_order_num = \app\common\model\fzly\order\Order::where(["guide_id" => $user_id])->whereIn('status',[2,3])->whereTime('createtime','d')->count();
        $data = [
            "money" => $money,
            "y_order_num" => $y_order_num,
            "t_order_num" => $t_order_num,
        ];
        //获取总收入
        $total = \app\common\model\fzly\order\Order::where(["guide_id"=>$user_id,"status"=>3])->sum("order_amount_total");
        $data['total_money'] = $total;
        $this->success("成功", $data);
    }


    /*
     * 获取发布产品类型
     */
    public function get_product_type(){
       $data = Type::where(["status" => "normal"])->order("weigh desc")->select();
       $this->success("成功", $data);
    }

    /*
     * 获取产品车型
     */
    public function get_product_car(){
        $pro = new Product();
        $data = $pro->getModelList();
        $arr = [];
        foreach ($data as $k => $v){
            $arr[$k]['id'] = $k;
            $arr[$k]['title'] = $v;
        }
        $this->success("成功", array_merge($arr));
    }

    /*
     * 获取产品时长
     */
    public function get_product_time(){
        $pro = new Product();
        $data = $pro->getDurationList();
        $arr = [];
        foreach ($data as $k => $v){
            $arr[$k]['id'] = $k;
            $arr[$k]['title'] = $v;
        }
        $this->success("成功", array_merge($arr));
    }

    /*
     * 发布产品/编辑产品
     */
    public function add_product(){
        $user_id = $this->auth->id;
        $detail = Detail::get(["user_id" => $user_id]);
        if (!$detail){
            $this->error("用户信息不存在");
        }
        if ($detail['is_dy']!=1) {
            $this->error("不是导游，不能发布产品");
        }
        $id = $this->request->post("id");//产品id
        $image = $this->request->post("image");//缩略图
        $title = $this->request->post("title");//产品标题
        $type_id = $this->request->post("type_id");//产品类型
        $model = $this->request->post("model/a");//车型
        $time = $this->request->post("time/a");//时长
        $address = $this->request->post("address");//景区地点
        $zm_image = $this->request->post("zm_image");//合作证明
        $content = $this->request->post("content","",null);//详细描述
        $price = $this->request->post("price");//价格
        if (!$image) {
            $this->error("缩略图不能为空");
        }
        if (!$title) {
            $this->error("产品标题不能为空");
        }
        if (!$type_id) {
            $this->error("产品类型不能为空");
        }
        if (!$address) {
            $this->error("景区地点不能为空");
        }
        if (!$zm_image) {
            $this->error("合作证明不能为空");
        }
        if ($price<=0) {
            $this->error("价格必须大于0");
        }
        $data = [
            "user_id" => $user_id,
            "image" => $image,
            "title" => $title,
            "type_id" => $type_id,
            "model" => implode(",",$model),
            "duration" => implode(",",$time),
            "jq_title" => $address,
            "zm_image" => $zm_image,
            "tw_content" => $content,
            "yd_multiplejson" => '[{"icon":"/assets/addons/fzly/img/icon.png","intro":"可明日订"},{"icon":"/assets/addons/fzly/img/icon.png","intro":"立即确认"}]',
            "guarantee" => '严选向导 - 管家服务 - 全球救援 - 先行赔付',
            "fy_content" => '费用包含司机服务费用,车辆使用费,车辆燃油费/电费,停车费,其他',
            "tg_content" => '用户取消损失费用按照优惠前金额收取,最高不超过实付金额.如需改期,请申请取消重新预定.订单不支持部分退',
            "tg_multiplejson" => '[{"time":"使用时间1天01:00 (含) 之前 ","fy":"50%"},{"time":"使用时间1天01:00 (不含) 之后 ","fy":"100%"}]',
            "price" => $price,
            "createtime" => time(),
            "updatetime" => time(),
        ];
        if ($id){
            unset($data['createtime']);
            unset($data['user_id']);
            $data['status'] = 1;
            $res = Product::update($data,["id" => $id]);
        }else{
            $res = Product::create($data);

        }
        if ($res){
            $this->success("发布成功,请等待审核");
        }else {
            $this->error("发布失败");
        }


    }

    /*
     * 上下架导游产品
     */
    public function product_status(){
        $user_id = $this->auth->id;
        $id = $this->request->post("id");
        $type = $this->request->post("type")??2;//2=上架 4=下架
        if (!$id) {
            $this->error("缺少产品id");
        }
        $product = Product::get($id);
        if (!$product){
            $this->error("产品不存在");
        }

        if ($product['user_id']!=$user_id){
            $this->error("不是该产品的发布者");
        }
        if ($product['status']==1){
            $this->error("未审核");
        }
        $data = [
            "status" => $type,
            "updatetime" => time(),
        ];
        $res = Product::update($data,["id" => $id]);
        if ($res){
            $this->success("操作成功");
        }else {
            $this->error("操作失败");
        }
    }

    /*
     * 导游产品降价
     */
    public function product_price()
    {
        $user_id = $this->auth->id;
        $id = $this->request->post("id");
        $price = $this->request->post("price");
        if (!$id) {
            $this->error("缺少产品id");
        }
        $product = Product::get($id);
        if (!$product) {
            $this->error("产品不存在");
        }
        if ($product['user_id']!= $user_id) {
            $this->error("不是该产品的发布者");
        }
        if ($price <= 0) {
            $this->error("价格必须大于0");
        }
        $data = [
            "price" => $price,
            "status" => 1,
            "updatetime" => time(),
        ];
        $res = Product::update($data, ["id" => $id]);
        if ($res) {
            $this->success("操作成功");
        } else {
            $this->error("操作失败");
        }
    }

    /*
     * 我的导游产品列表
     */
    public function product_list(){
        $user_id = $this->auth->id;
        $type = $this->request->post("type") ?? 1;   //1=产品 2=已下架
        $page   = $this->request->post('page')??1;   //页码
        $limit  = $this->request->post('limit')??10; //每页显示条数
        $where = [];
        if ($type==1){
            $where['status'] = ['in',[1,2,3]];
        }else{
            $where['status'] = ['=',4];
        }
        $data = Product::where(["user_id" => $user_id])->where($where)->field("id,title,image,price,view_nums,sc_nums,status,yd_multiplejson,guarantee")->order("weigh desc")->paginate(["page"=>$page,"list_rows"=>$limit])
            ->each(function ($item){
                $item->image = cdnurl($item->image,true);
                if ($item->yd_multiplejson){
                    $item->yd_multiplejson = json_decode($item->yd_multiplejson,true);
                }
            });
        $this->success("成功", $data);
    }

    /*
     * 收益提现
     */
    public function withdraw(){
        $user_id = $this->auth->id;
        $money = $this->request->post("money");
        $id = $this->request->post("id")??0;//提现方式
        //校验$withdraw_money是否是数字类型
        if (!is_numeric($money)){
            $this->error("提现金额必须为数字类型");
        }
        if (!$money){
            $this->error("提现金额不能为空");
        }
        if ($money<=0){
            $this->error("提现金额必须大于0");
        }

        $user = \app\admin\model\User::get($user_id);
        $detail = Detail::get(["user_id" => $user_id]);
        if (!$detail){
            $this->error("用户信息不存在");
        }
        if (!$detail['is_dy']){
            $this->error("不是导游，不能提现");
        }
        if ($id>0){
            $bank = Bank::get($id);
            if (!$bank){
                $this->error("提现方式不存在");
            }
            if ($bank['user_id']!=$user_id){
                $this->error("不是该提现方式的发布者");
            }
            if ($bank['status']!=1){
                $this->error("提现方式未审核");
            }
        }else{
            if (!$detail['openid']){
                $this->error("请先绑定微信");
            }
            $bank = ["type"=>"2"];
        }


        $money_t = $user->money;
        if ($money>$money_t){
            $this->error("提现金额大于余额");
        }

        //判断是否有未审核的提现记录
        $with = Withdraw::where(['user_id' => $user_id,'status' => 1])->find();
        if ($with){
            $this->error("有未审核的提现记录，请等待审核");
        }

        $leave_money = bcsub($money_t,$money,2);

//        if ($this->config['isauto'] && $bank['type']==2){
//            $obj = new WxWithdrawal();
//            $draw_no = "TX".fzly_orderNo();
//            $res = $obj->wx_withdrawal($detail['openid'],$draw_no,$money);
//            Log::write("提现结果：".json_encode($res));
//            if (isset($res['batch_id'])){
//                //减少收益
//                $user->money = 0;
//                $user->save();
//                //添加提现记录
//                $data = [
//                    "user_id" => $user_id,
//                    "withdraw_money" => $money,
//                    "leave_withdraw_money" => $leave_money,
//                    "createtime" => time(),
//                    "type" => json_encode($bank),
//                    "status" => 4,
//                    "draw_no" => $draw_no,
//                    "batch_id" => $res['batch_id'],
//                ];
//                Withdraw::create($data);
//                $this->success("提现成功");
//            }else{
//                //添加提现记录
//                $data = [
//                    "user_id" => $user_id,
//                    "withdraw_money" => $money,
//                    "leave_withdraw_money" => $money,
//                    "createtime" => time(),
//                    "type" => json_encode($bank),
//                    "status" => 2,
//                    "draw_no" => $draw_no,
//                ];
//                Withdraw::create($data);
//                $this->error("提现失败:".json_encode($res));
//                $this->error("提现失败");
//            }
//        }else{
            //添加提现记录
            $data = [
                "user_id" => $user_id,
                "withdraw_money" => $money,
                "leave_withdraw_money" => $leave_money,
                "createtime" => time(),
                "type" => json_encode($bank),
                "status" => 1,
            ];
            Withdraw::create($data);
            $this->success("提现申请成功");

//        }
    }

    /*
     * 提现记录
     */
    public function withdraw_list(){
        $user_id = $this->auth->id;
        $page = $this->request->post('page')??1;   //页码
        $limit  = $this->request->post('limit')??10; //每页显示条数
        $where = [];
        $where['user_id'] = ['=', $user_id];
        $data = Withdraw::where($where)->order("id desc")->paginate(["page"=>$page,"list_rows"=>$limit])
            ->each(function ($item){
                $array = json_decode($item->type, true);
                $item->array = $array;
                $item->createtime = date('Y-m-d H:i:s', $item->createtime);
                if ($item->array['type']==1){
                    $item->type_texts = "银行卡提现";
                }elseif ($item->array['type']==2){
                    $item->type_texts = "微信提现";
                }elseif ($item->array['type']==3){
                    $item->type_texts = "支付宝提现";
                }
            });
        $this->success("成功", $data);
    }

    /*
     * 获取收入明细
     */
    public function income_detail(){
        $user_id = $this->auth->id;
        $year = $this->request->post('year');
        $month = $this->request->post('month');
        if (!$year){
            $this->error("缺少年份");
        }
        if ($month){
            $daysArray = fzly_getDaysArrayWithWeekday($year,$month);
            $sum = 0;
            foreach ($daysArray as &$dv){
                //查询每一天的收益
                $t = explode('-',$dv['date']);
                $sy = Money::where(['y'=>$t[0],'m'=>$t[1],'d'=>$t[2],'gudie_id'=>$user_id])->sum('money');
                $sum = $sum+$sy;
                if ($sy){
                    $dv['money'] = $sy;
                }else{
                    $dv['money'] = 0;
                }
                $dv['date'] = $t[2];
            }
            $this->success("success",['month_sum'=>$sum,'days_array'=>$daysArray]);
        }else{
            $arr = [];
            $sum = 0;
            for ($i=1;$i<=12;$i++){
                $i = sprintf("%02d", $i);
                //查询每月的收益
                $sy = Money::where(['y'=>$year,'m'=>$i,'gudie_id'=>$user_id])->sum('money');
                $sum = $sum+$sy;
                if ($sy){
                    $dv['money'] = $sy;
                }else{
                    $dv['money'] = 0;
                }
                $arr[] = [
                    "date" => sprintf("%02d", $i),
                    "money" => $sy,
                ];

            }
            $this->success("success",['month_sum'=>$sum,'days_array'=>$arr]);
        }

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

        $where['guide_id'] = ['=', $user_id];
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
     * 添加提现账号
     */
    public function add_withdrawal_account(){
        $user_id = $this->auth->id;
        $type = $this->request->post("type")??1;   //1=银行卡 2=微信 3=支付宝
        $id = $this->request->post("id");   //提现id
        if ($type==1) {
            $bank_code = $this->request->post('bank_code'); //银行卡号
            $bank_mobile = $this->request->post('bank_mobile'); //银行卡预留手机号
            $code = $this->request->post('code'); //短信验证码
            if (empty($bank_code)) {
                $this->error("银行卡号不能为空");
            }
            if (empty($bank_mobile)) {
                $this->error("银行卡预留手机号不能为空");
            }
            if (empty($code)) {
                $this->error("短信验证码不能为空");
            }
            $ret = Smslib::check($bank_mobile, $code, 'register');
            if (!$ret && $code != '0000') {
                $this->error("短信验证码不正确");
            }
            //根据银行卡号得到银行卡名称
            $band_res = $this->checkLuhn($bank_code);
            if(!$band_res){
                $this->error("银行卡格式错误");
            }
            $result = Http::get("https://ccdcapi.alipay.com/validateAndCacheCardInfo.json?_input_charset=utf-8&cardNo={$bank_code}&cardBinCheck=true");
            $result = json_decode($result, true);
            if (empty($result) || !is_array($result)) {
                $this->error("服务异常");
            }
            $bank_name = isset(self::$bank_info[$result['bank']]) ? self::$bank_info[$result['bank']] : '';  // 银行名称
            $bank_icon = isset(self::$card_icon[$result['bank']]) ? self::$card_icon[$result['bank']] : '';  // 银行图标
            $bran_type = self::$card_type[$result['cardType']];

            if ($id){
                Bank::update([
                    "bank_name" => $bank_name,
                    "bank_icon" => $bank_icon,
                    "bank_type" => $bran_type,
                    "bank_code" => $bank_code,
                    "bank_mobile" => $bank_mobile,
                    "status" => 0,
                    "updatetime" => time(),
                ],["id" => $id]);
            }else{
                //添加银行卡
                Bank::create([
                    "user_id" => $user_id,
                    "type" => 1,
                    "bank_name" => $bank_name,
                    "bank_icon" => $bank_icon,
                    "bank_type" => $bran_type,
                    "bank_code" => $bank_code,
                    "bank_mobile" => $bank_mobile,
                    "status" => 0,
                    "createtime" => time(),
                    "updatetime" => time(),
                ]);
            }


        }else{
            $code     = $this->request->post('zh_code');               // 账号
            if (empty($code)){
                $this->error("账号不正确");
            }
            if($type==2){
                //添加收款码
                if ($id){
                    Bank::update([
                        "wx_code" => $code,
                        "status" => 0,
                        "updatetime" => time(),
                    ],["id"=>$id]);
                }else{
                    Bank::create([
                        "user_id" => $user_id,
                        "type" => $type,
                        "wx_code" => $code,
                        "status" => 0,
                        "createtime" => time(),
                        "updatetime" => time(),
                    ]);
                }

            }else{
                if ($id){
                    Bank::update([
                        "zfb_code" => $code,
                        "status" => 0,
                        "updatetime" => time(),
                    ],["id"=>$id]);
                }else{
                    //查看是否已经绑定过支付宝
                    $bank = Bank::where(["user_id" => $user_id, "type" => 3])->find();
                    if ($bank){
                        $this->error("已经绑定过支付宝");
                    }

                    //添加收款码
                    Bank::create([
                        "user_id" => $user_id,
                        "type" => $type,
                        "zfb_code" => $code,
                        "status" => 0,
                        "createtime" => time(),
                        "updatetime" => time(),
                    ]);
                }

            }

        }
        $this->success("提交成功");


    }

    private function checkLuhn($card){
        $len=strlen($card);
        $all=[];
        $sum_odd=0;
        $sum_even=0;
        for($i=0;$i<$len;$i++){
            $all[]=substr($card,$len-$i-1,1);
        }
        //all 里的偶数key都是我们要相加的奇数位
        for($k=0;$k<$len;$k++){
            if($k % 2 ==0){
                $sum_odd+=$all[$k];
            }else{
                //奇数key都是要相加的偶数和
                if($all[$k] * 2 >= 10){
                    $sum_even+=$all[$k] * 2 - 9;
                }else{
                    $sum_even+=$all[$k]*2;
                }
            }
        }
        $total=$sum_odd+$sum_even;
        if($total % 10 == 0){
            return true;
        }else{
            return false;
        }
    }


    /*
     * 解绑提现账号
     */
    public function del_withdrawal_account(){
        $id = $this->request->post("id");   //提现账号
        if (!$id){
            $this->error("账号id不能为空");
        }
        $res = Bank::get($id);
        if (!$res){
            $this->error("提现账户不存在");
        }
        Bank::destroy($id);
        $this->success("解绑成功");

    }

    /*
     * 获取提现账号列表
     */
    public function withdrawal_account_list(){
        $user_id = $this->auth->id;
        $status = $this->request->post('status'); //提现状态
        $where = [];
        if ($status){
            $where['status']= ['=', $status];
        }else{
            $where['status'] = ['in', [0, 1]];
        }
        $de = \app\admin\model\User::get(["id" => $user_id]);
        if (!$de){
            $this->error("用户信息不存在");
        }
        $w = Withdraw::where(["user_id" => $user_id,"status"=>1])->order("id desc")->limit(1)->find();
        if ($w){
            $money = bcsub($de->money, $w->withdraw_money, 2);
        }else{
            $money = $de->money;
        }
        $data = Bank::where(["user_id"=>$user_id])->where($where)->select()
            ->each(function ($item){
                if ($item->type==1){
                    $img = "./".$item->bank_icon;
                    $average = new Imagick($img);
                    $average->quantizeImage(10, Imagick::COLORSPACE_RGB, 0, false, false);
                    $average->uniqueImageColors();

                    $colorarr = $this->GetImagesColor($average);
                    $r = $g = $b = 0;
                    foreach ($colorarr as $val) {
                        $r += $val['r'];
                        $g += $val['g'];
                        $b += $val['b'];
                    }
                    $r = round($r / 10);
                    $g = round($g / 10);
                    $b = round($b / 10);
                    $item->rgb = "rgb({$r},{$g},{$b})";
                }elseif($item->type==2){
                    $item->rgb = "#00C2A0";
                }else{
                    $item->rgb = "#6C80FF";
                }
                $item->bank_icon = cdnurl($item->bank_icon,true);
            });
        $this->success("success",["data"=>$data,"money"=>$money]);
    }


    private function GetImagesColor(Imagick $im)
    {
        $colorarr = array();
        $it = $im->getPixelIterator();
        $it->resetIterator();
        while ($row = $it->getNextIteratorRow()) {
            foreach ($row as $pixel) {
                $colorarr[] = $pixel->getColor();
            }
        }
        return $colorarr;
    }


}
