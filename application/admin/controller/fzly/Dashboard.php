<?php

namespace app\admin\controller\fzly;

use app\admin\model\Admin;
use app\admin\model\User;
use app\common\controller\Backend;
use app\common\model\Attachment;
use app\common\model\fzly\admission\Admission;
use app\common\model\fzly\content\Content;
use app\common\model\fzly\order\Order;
use app\common\model\fzly\trends\Trends;
use app\common\model\fzly\user\Detail;
use app\common\model\fzly\user\guide\Product;
use fast\Date;
use think\Db;

/**
 * 控制台
 *
 * @icon   fa fa-dashboard
 * @remark 用于展示当前系统中的统计数据、统计报表及重要实时数据
 */
class Dashboard extends Backend
{

    /**
     * 查看
     */
    public function index()
    {
        try {
            \think\Db::execute("SET @@sql_mode='';");
        } catch (\Exception $e) {

        }
        $column = [];
        $starttime = Date::unixtime('day', -6);
        $endtime = Date::unixtime('day', 0, 'end');
        $joinlist = Db::name('fzly_order')->where('createtime', 'between time', [$starttime, $endtime])
            ->where('status', 'in', [2, 3])
            ->field('createtime, status, COUNT(*) AS nums, DATE_FORMAT(FROM_UNIXTIME(createtime), "%Y-%m-%d") AS join_date')
            ->group('join_date')
            ->select();
        for ($time = $starttime; $time <= $endtime;) {
            $column[] = date("m-d", $time);
            $time += 86400;
        }
        $orderlist = array_fill_keys($column, 0);
        foreach ($joinlist as $k => $v) {
            $orderlist[$v['join_date']] = $v['nums'];
        }


        $joinlist = Db("user")->where('jointime', 'between time', [$starttime, $endtime])
            ->field('jointime, status, COUNT(*) AS nums, DATE_FORMAT(FROM_UNIXTIME(jointime), "%Y-%m-%d") AS join_date')
            ->group('join_date')
            ->select();
        for ($time = $starttime; $time <= $endtime;) {
            $column[] = date("m-d", $time);
            $time += 86400;
        }
        $userlist = array_fill_keys($column, 0);
        foreach ($joinlist as $k => $v) {
            $userlist[$v['join_date']] = $v['nums'];
        }





        //获取门票、攻略、游记、美食、导游产品的访问量
        $z21 = [
            ['name'=>"门票",'value'=>Admission::sum('view_nums')],
            ['name'=>"攻略",'value'=>Content::where(['type'=>1])->sum('view_nums')],
            ['name'=>"游记",'value'=>Content::where(['type'=>2])->sum('view_nums')],
            ['name'=>"美食",'value'=>Content::where(['type'=>3])->sum('view_nums')],
            ['name'=>"导游产品",'value'=>Product::sum('view_nums')],
        ];

        //门票销量排行
        $mp_o = Order::whereIn('status',[2,3])->where("order_type",1)->field('goods_id,count(*) as nums,sum(order_amount_total) as pp')->order('nums','desc')->group('goods_id')->limit(5)->select()
        ->each(function ($tem){
            $ad = Admission::get($tem['goods_id']);
            if ($ad){
                $tem->title = $ad['title'];
            }else{
                $tem->title = "门票已删除";
            }
        });
        //导游产品销量排行
        $mp_pro = Order::whereIn('status',[2,3])->where("order_type",2)->field('goods_id,count(*) as nums,sum(order_amount_total) as pp')->order('nums','desc')->group('goods_id')->limit(5)->select()
            ->each(function ($tem){
                $ad = Product::get($tem['goods_id']);
                if ($ad){
                    $tem->title = $ad['title'];
                }else{
                    $tem->title = "产品已删除";
                }
            });



        $this->view->assign([
            'mp'         => Admission::count(),
            'dy'        => Detail::where(["is_dy"=>1])->count(),
            'cp'        => Product::count(),
            'user'     => User::count(),
            'j_gl'     => Content::where(['type'=>1,"status"=>3])->whereTime('createtime','d')->count(),
            'z_gl'     => Content::where(['type'=>1,"status"=>3])->whereTime('createtime','yesterday')->count(),
            'j_yj'     => Content::where(['type'=>2,"status"=>3])->whereTime('createtime','d')->count(),
            'z_yj'     => Content::where(['type'=>2,"status"=>3])->whereTime('createtime','yesterday')->count(),
            'j_dt'     => Trends::where(["status"=>3])->whereTime('createtime','d')->count(),
            'z_dt'     => Trends::where(["status"=>3])->whereTime('createtime','yesterday')->count(),
            'j_dd'     => Order::whereIn('status',[2,3])->whereTime('createtime','d')->count(),
            'j_user'     => User::whereTime('createtime','d')->count(),
            'j_daoy'     => Detail::where(["is_dy"=>1])->whereTime('createtime','d')->count(),
            'mp_list'=>Admission::order('view_nums desc')->field('id,title,view_nums')->limit(5)->select(),
            'mp_xl'  =>$mp_o,
            'dy_list'  =>Detail::where(["is_dy"=>1])->order('fs_s desc')->field('id,user_id,fs_s')->limit(5)->select()
                        ->each(function ($tem){
                            $user = User::get($tem['user_id']);
                            if ($user){
                                $tem->user_name = $user['username'];
                            }else{
                                $tem->user_name = "用户已删除";
                            }
                        }),
            "mp_pro"=>$mp_pro,
        ]);

        $this->assignconfig('column', array_keys($orderlist));
        $this->assignconfig('orderdata', array_values($orderlist));
        $this->assignconfig('column1', array_keys($userlist));
        $this->assignconfig('userdata2', array_values($userlist));
        $this->assignconfig('z21', ['门票', '攻略', '游记', '美食', '导游产品']);
        $this->assignconfig('z22', $z21);

        return $this->view->fetch();
    }

}
