<?php

namespace addons\fzly\controller;

use app\common\controller\Api;
use app\common\model\fzly\admission\Type;
use app\common\model\fzly\coupon\Receive;
use app\common\model\fzly\order\Evaluate;
use app\common\model\fzly\user\Comment;
use app\common\model\fzly\user\guide\Product;
use app\common\model\fzly\user\Hitory;
use think\Request;

class Admission extends Api
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
    * 门票类型
    */
    public function ticket_type(){
        $data = Type::where(['status'=>'normal'])->order('weigh desc,id desc')->field('id,title')->select();
        $this->success('success', $data);
    }

    /*
     * 门票列表
     */
    public function ticket_list(){
        $user_id = $this->auth->id;
        $city = $this->request->header('city')??urlencode($this->config['city']);//城市 默认郑州
        $lon = $this->request->header("lon")??'113.663221';
        $lat = $this->request->header("lat")??'34.7568711';
        $sort   = $this->request->post('sort')??1;  //排序方式 1:综合 2:价格最低 3:价格最高 4：距离最短
        $page   = $this->request->post('page')??1;  //页码
        $limit  = $this->request->post('limit')??10; //每页显示条数
        $search  = $this->request->post('search')??""; //搜索条件
        $type_id  = $this->request->post('type_id/a')??""; //门票类型
        $flag  = $this->request->post('flag'); //门票标识
        $city   = urldecode($city);
        $where = [];
        $where['status'] = ['=', 'normal'];
        if($search){
            $where['title'] = ['like',"%$search%"];
        }
        if ($flag){
            $where['flag'] = ['=', $flag];
        }
        if($type_id){
            $t_where = '';
            $count = count($type_id);
            foreach ($type_id as $k => $v) {
                if($k==$count-1){
                    $t_where.= "FIND_IN_SET($v,type_ids)";
                }else {
                    $t_where.= "FIND_IN_SET($v,type_ids) OR ";
                }
            }
        }else{
            $t_where = '1=1';
        }
        $order = 'weigh desc,id desc';
        if ($sort==2){
            $order = 'lowest_price asc';
        }elseif ($sort==3){
            $order = 'highest_price desc';
        }elseif ($sort==4){
            $order = 'distance asc';
        }
        $data = \app\common\model\fzly\admission\Admission::where($where)->where($t_where)
            ->where('city','like',"%$city%")
            ->order($order)->field("id,title,image,type_ids,address_xx,tags,lowest_price,highest_price,".fzly_getDistanceBuilder($lat, $lon))->paginate(["page"=>$page,"list_rows"=>$limit])
            ->each(function ($item){
                $item->image = cdnurl($item->image,true);
                //评论数
                $item->comment_count = Comment::where(['con_id'=>$item->id])->count();

            });
        if ($search && $user_id){
            //记录搜索
            $h = Hitory::get(["user_id"=>$user_id,"title"=>$search]);
            if (!$h){
                $h = new Hitory();
                $h->user_id = $user_id;
                $h->title = $search;
                $h->number = 1;
                $h->createtime = time();
                $h->updatetime = time();
                $h->save();
            }else{
                $h->setInc('number');
                $h->save();
            }
        }
        $this->success('success', $data);
    }


    /*
     * 门票详情
     */
    public function ticket_detail()
    {
        $u = $this->auth->id;
        $id = $this->request->post('id');
        $data = \app\common\model\fzly\admission\Admission::where(['id' => $id])->find();
        if (!$data) {
            $this->error('门票不存在');
        }
        if ($data->status != 'normal'){
            $this->error('门票已下架');
        }
        if ($data->images){
            $images = explode(',', $data->images);
            foreach ($images as $k => &$v) {
                $v = cdnurl($v, true);
            }
            $data->images = $images;
        }
        if ($data->mp_multiplejson){
            $data->mp_multiplejson = json_decode($data->mp_multiplejson, true);
        }else{
            $data->mp_multiplejson = [];
        }
        if ($data->yd_multiplejson){
            $yd_multiplejson = json_decode($data->yd_multiplejson, true);
            foreach ($yd_multiplejson as $k => &$yv) {
                if ($yv['icon']){
                    $yv['icon'] = cdnurl($yv['icon'], true);
                }
            }
            $data->yd_multiplejson = $yd_multiplejson;
        }else{
            $data->yd_multiplejson = [];
        }
        $prefix = request()->domain() ;
        // 用正则表达式匹配图片地址并添加前缀
        $updatedHtmlContent = preg_replace('/(<img src=")([^"]*)"/i', '$1' . $prefix . '$2"', $data['content']);
        $data['content'] = $updatedHtmlContent ;
        $data->hidden(['flag','image','lowest_price','highest_price']);
        if (!$u){
            $data['is_y'] = 1;
        }else{
            //判断是否有可领取的优惠券
            $u_r = Receive::where(['user_id'=>$u])->column('coupon_id');
            $r = \app\common\model\fzly\coupon\Coupon::where(['status'=>'normal'])->column('id');
            //判断$r是否全部包含$u_r 如果全部包含 则$data['is_y'] = 0 否则为0
            if (empty(array_diff($r,$u_r))){
                $data['is_y'] = 0;
            }else {
                $data['is_y'] = 1;
            }
        }
        if (!$data['common_nums']){
            $data['score'] = 5;
        }
        \app\common\model\fzly\admission\Admission::where(['id' => $id])->setInc('view_nums');


        $this->success('success', $data);
    }

    /*
     * 猜你想去
     */
    public function guess_like(){
        $city = $this->request->header('city') ?? urlencode($this->config['city']);//城市 默认郑州
        $page   = $this->request->post('page')??1;  //页码
        $limit  = $this->request->post('limit')??10; //每页显示条数
        $id = $this->request->post('id');
        $city   = urldecode($city);
        $admission = \app\common\model\fzly\admission\Admission::where(['id' => $id])->find();
        $data = \app\common\model\fzly\admission\Admission::
        where(['status' => 'normal','city' => ['like',"%$city%"]])
            ->where('id','not in',$admission['id'])->field("id,title,image,type_ids,flag,lowest_price")->page($page,$limit)->select()
            ->each(function ($item) {
                $item->image = cdnurl($item->image, true);
            });
        $this->success('success', $data);
    }

    /*
     * 门票评论
     */
    public function comment(){
        $id = $this->request->post('id');
        $type = $this->request->post('type')??1;
        if ($type==1){
            $data = \app\common\model\fzly\admission\Admission::where(['id' => $id])->find();
            if (!$data) {
                $this->error('门票不存在');
            }
            if ($data->status != 'normal'){
                $this->error('门票已下架');
            }
        }else{
            $p = Product::get(['id' => $id]);
            if (!$p) {
                $this->error('产品不存在');
            }
            $u = \app\admin\model\User::get($p->user_id);
            if (!$u){
                $this->error("用户信息不存在");
            }
        }

        $page   = $this->request->post('page')??1;  //页码
        $limit  = $this->request->post('limit')??10; //每页显示条数
        $data = Evaluate::where(['order_type'=>$type,"goods_id"=>$id])->order("id","desc")->paginate(["page"=>$page,"list_rows"=>$limit])
            ->each(function ($item){
                $user = \app\admin\model\User::get($item->user_id);
                if ($item->is_nm){
                    $item->username = "匿名用户";
                    $item->avatar = cdnurl($user->avatar,true);
                }else{
                    $item->username = $user->username;
                    $item->avatar = cdnurl($user->avatar,true);
                }
                if ($item->images){
                    $images = explode(',', $item->images);
                    foreach ($images as $k => &$v) {
                        $v = cdnurl($v, true);
                    }
                    $item->images = $images;
                }
                $item->creattime = date("Y-m-d",$item->createtime);
            });
        //先获取类型的评论标签
        $plbq = \app\common\model\fzly\evaluate\Evaluate::get(["type"=>$type]);
        if (!$plbq['tag_json']) {
            $tagss = [];
        }else{
            $t1 = json_decode($plbq['tag_json'],true);
            $tagss = [];
            $tags = Evaluate::where(['order_type'=>$type,"goods_id"=>$id])->column('tag_json');
            foreach ($t1 as $k => $v) {
                $tagss[$k]['name'] = $v;
                $tagss[$k]['num'] = 0;
                foreach ($tags as $k1 => $v1){
                    $v1 = json_decode($v1);
                    if (in_array($v, $v1)){
                        $tagss[$k]['num'] = $tagss[$k]['num']+1;
                    }
                }
            }
        }
        if ($type==1){
            $xx = [];
        }else{
            $xx['ffyh'] = \app\common\model\fzly\order\Order::where(['order_type' => 2, 'guide_id' => $u['id'],"status"=>3])->group('user_id')->count();
            $xx['hps'] = Evaluate::where(['order_type' => 2, 'guide_id' => $u['id']])->where("score",">",4)->count();
            if ($xx['hps']==0){
                $xx['hpl'] = 0;
            }else{
                $xx['hpl'] = bcmul(bcdiv($xx['hps'],Evaluate::where(['order_type' => 2, 'guide_id' => $u['id']])->count()),100);
            }
            $xx['zz'] = $this->calculateYearsFromTimestamp($u['createtime']);
        }

        $this->success('success', ["data"=>$data,"tags"=>$tagss,'xx'=>$xx]);
    }

    private function calculateYearsFromTimestamp($timestamp)
    {
        $current_time = time();
        $time_difference = $current_time - $timestamp;
        $years_difference = floor($time_difference / (60 * 60 * 24 * 365));
        if ($years_difference==0){
            $years_difference = 1;
        }
        return $years_difference;
    }
}
