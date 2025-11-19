<?php

namespace addons\fzly\controller;

use app\admin\model\User as U;
use app\common\controller\Api;
use app\common\model\Area;
use app\common\model\fzly\trends\Trends;
use app\common\model\fzly\trends\Type;
use app\common\model\fzly\user\Comment;
use app\common\model\fzly\user\Detail;
use app\common\model\fzly\user\Dz;
use app\common\model\fzly\user\Follow;
use app\common\model\fzly\user\guide\Product;
use app\common\model\fzly\user\Sc;
use think\Request;

class Content extends Api
{
    // 无需登录的接口,*表示全部
    protected $noNeedLogin = ['trends','lists','detail','get_comment'];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['*'];

    protected $config = [];

    public function __construct(Request $request = null)
    {
        $this->config = get_addon_config("fzly");
        parent::__construct($request);
    }

    /*
     * 动态分类
     */
    public function trends_type(){
        $data = Type::where('status','=',"normal")->order('weigh asc')->field('id,title')->select();
        $this->success('success', $data);
    }

    /*
     * 动态列表
     */
    public function trends(){
        $user_id = $this->auth->id;
        $page = $this->request->post('page') ?? 1;  //页码
        $df_id = $this->request->post('df_id');  //对方id
        $limit = $this->request->post('limit') ?? 10; //每页显示条数
        $type = $this->request->post('type')??1; //1=关注,2=广场,3=用户
        $type_id = $this->request->post('type_id'); //分类id
        if ($type == 1){
            $ids = Follow::where(['user_id'=>$user_id])->column('df_id');
            $where['user_id'] = ['in',$ids];
        }elseif ($type == 3){
            $where['user_id'] = ['=',$df_id];
        }
        if ($type_id){
            $where['type_id'] = ['=',$type_id];
        }
        $data = Trends::where($where)->where('status','=',3)->field('id,type_id,user_id,title,content,images,createtime,updatetime,status,dz_nums,common_nums')->order('id desc')->paginate(["page"=>$page,"list_rows"=>$limit])
            ->each(function ($item){
                if ($item->images){
                    $images = explode(',',$item->images);
                    foreach ($images as $k => &$v){
                        $v = cdnurl($v,true);
                    }
                    $item->images = $images;
                }
                $user = \app\admin\model\User::get($item->user_id);
                if ($user){
                    $item->username = $user->username;
                    $item->avatar = cdnurl($user->avatar,true);
                }else{
                    $item->username = "暂无";
                    $item->avatar = cdnurl("/assets/img/avatar.png",true);
                }
                $item->uptime = fzly_format_dates($item->createtime);
                $is_dz = 0;
                $dz_res = Dz::where(['user_id'=>$this->auth->id,'con_id'=>$item->id,'type'=>4])->find();
                if ($dz_res){
                    $is_dz = 1;
                }
                $is_gz = 0;
                $gz_res = Follow::where(['user_id'=>$this->auth->id,'df_id'=>$item->user_id])->find();
                if ($gz_res){
                    $is_gz = 1;
                }
                $item->is_gz = $is_gz;
                $item->is_dz = $is_dz;
            });
        $this->success('success', $data);
    }

    /*
     * 点赞/取消点赞
     */
    public function dz(){
        $type = $this->request->post('type')??1; //1=攻略,2=游记,3=美食,4=动态
        $user_id = $this->auth->id;
        $id = $this->request->post('id'); //内容id
        $option = $this->request->post('option')??1; //1=点赞,2=取消点赞
        if (!$id){
            $this->error('缺少动态id');
        }

        if ($type == 4){
            $trends = Trends::get($id);
        }else{
            $trends = \app\common\model\fzly\content\Content::get($id);
        }

        $dz = Dz::where(['user_id'=>$user_id,'con_id'=>$id,'type'=>$type])->find();
        if ($option==1){
            if ($dz){
                $this->error('已经点赞过了');
            }
            Dz::create([
                "user_id"=>$user_id,
                "con_id" => $id,
                "type" => $type,
                "createtime"=>time(),
                "updatetime" => time(),
            ]);
            $trends->setInc('dz_nums');
            $trends->save();
            Detail::where(['id'=>$trends['user_id']])->setInc('like_s');
        }else{
            if (!$dz){
                $this->error('还没点赞');
            }
            Dz::where(['user_id'=>$user_id,'con_id'=>$id,'type'=>$type])->delete();
            $trends->setDec('dz_nums');
            $trends->save();
            Detail::where(['id'=>$trends['user_id']])->setDec('like_s');
        }
        $this->success('success');
    }

    /*
     * 收藏/取消收藏
     */
    public function sc(){
        $type = $this->request->post('type')??1; //1=攻略,2=游记,3=美食,4=导游,5=导游产品
        $user_id = $this->auth->id;
        $id = $this->request->post('id'); //内容id
        $option = $this->request->post('option')??1; //1=收藏,2=取消收藏
        if (!$id){
            $this->error('缺少内容id');
        }

        if ($type <=3){
            $trends = \app\common\model\fzly\content\Content::get($id);
        }elseif ($type == 5){
            $trends = Product::get($id);
        }
        $sc = Sc::where(['user_id'=>$user_id,'con_id'=>$id,'type'=>$type])->find();
        if ($option==1){
            if ($sc){
                $this->error('已经收藏过了');
            }
            Sc::create([
                "user_id"=>$user_id,
                "con_id" => $id,
                "type" => $type,
                "createtime"=>time(),
                "updatetime" => time(),
            ]);
            if (isset($trends)){
                $trends->sc_nums += 1;
                $trends->save();
            }

        }else{
            if (!$sc){
                $this->error('还没收藏');
            }
            Sc::where(['user_id'=>$user_id,'con_id'=>$id,'type'=>$type])->delete();
            if (isset($trends)){
                $trends->sc_nums -= 1;
                $trends->save();
            }

        }
        $this->success('success');
    }


    /*
     * 内容列表
     */
    public function lists(){
        $user_id = $this->auth->id;
        $page   = $this->request->post('page')??1;  //页码
        $limit  = $this->request->post('limit')??10; //每页显示条数
        $id  = $this->request->post('id'); //景区id
        $df_id = $this->request->post('df_id');  //对方id
        $type  = $this->request->post('type')??1; //1=攻略,2=游记,3=美食
        $search  = $this->request->post('search'); //搜索条件
        $city = $this->request->header('city')??urlencode($this->config['city']);//城市 默认郑州
        $city   = urldecode($city);
        if ($df_id!=$user_id){
            $where['status'] = ['=',3];
        }
        if($id) {
            $where['mp_id'] = ['=',$id];
        }
        if ($df_id){
            $where['user_id'] = ['=',$df_id];
        }
        if($type) {
            $where['type'] = ['=',$type];
        }
        if ($search){
            $where['title'] = ['like',"%$search%"];
        }
        $data = \app\common\model\fzly\content\Content::where($where)
            ->where('city','like',"%$city%")
            ->field('id,city,type,title,image,images,view_nums,dz_nums,user_id,sc_nums,createtime,content,status')->paginate(["page"=>$page,"list_rows"=>$limit])
            ->each(function ($item)use ($type){
                if ($item->image){
                    $item->image = cdnurl($item->image,true);
                }else{
                    $images = explode(',',$item->images)[0];
                    $item->image = cdnurl($images,true);
                }
                $user = \app\admin\model\User::get($item->user_id);
                if ($user){
                    $item->username = $user->username;
                    $item->avatar = cdnurl($user->avatar,true);
                }else{
                    $item->username = "暂无";
                    $item->avatar = cdnurl("/assets/img/avatar.png",true);
                }
                $is_sc = 0;
                $dz_res = Sc::where(['user_id'=>$this->auth->id,'con_id'=>$item->id,'type'=>$type])->find();
                if ($dz_res){
                    $is_sc = 1;
                }
                $item->is_sc = $is_sc;
                $is_dz = 0;
                $dz_res1 = Dz::where(['user_id'=>$this->auth->id,'con_id'=>$item->id,'type'=>$type])->find();
                if ($dz_res1){
                    $is_dz = 1;
                }
                $item->is_dz = $is_dz;
                $item->createtime = date('Y-m-d',$item->createtime);
                if (strpos($item->city,'/')!==false){
                    $item->city = explode('/',$item->city)[1];
                }
                if ($item->view_nums==0){
                    $item->tj_user = [];
                }elseif ($item->view_nums<=3){
                    $item->tj_user = \app\common\model\fzly\user\User::where(['status'=>'normal'])->orderRaw("rand()")->field("username,avatar")->limit($item->view_nums)->select()
                        ->each(function ($item){
                            $item->avatar = cdnurl($item->avatar,true);
                        });
                }else{
                    $item->tj_user = \app\common\model\fzly\user\User::where(['status'=>'normal'])->orderRaw("rand()")->field("username,avatar")->limit(3)->select()
                        ->each(function ($item){
                            $item->avatar = cdnurl($item->avatar,true);
                        });
                }

                $item->c_content = strip_tags($item->content);
            });
        $this->success('success', $data);
    }

    /*
     * 内容详情
     */
    public function detail(){
        $id  = $this->request->post('id'); //内容id
        if (!$id){
            $this->error('缺少内容id');
        }
        $data = \app\common\model\fzly\content\Content::get($id);
        if (!$data) {
            $this->error('内容不存在');
        }
        $data->view_nums += 1;
        $data->save();
        $data->image = cdnurl($data->image,true);
        $images = explode(',',$data->images);
        foreach ($images as $k => &$v){
            $v = cdnurl($v,true);
        }
        $data->images = $images;
        $user = \app\admin\model\User::get($data->user_id);
        if ($user){
            $data->username = $user->username;
            $data->avatar = cdnurl($user->avatar,true);
        }else{
            $data->username = "暂无";
            $data->avatar = cdnurl("/assets/img/avatar.png",true);
        }
        $is_sc = 0;
        $dz_res = Sc::where(['user_id'=>$this->auth->id,'con_id'=>$id,'type'=>$data['type']])->find();
        if ($dz_res){
            $is_sc = 1;
        }
        $data['is_sc'] = $is_sc;
        $is_dz = 0;
        $dz_res = Dz::where(['user_id'=>$this->auth->id,'con_id'=>$id,'type'=>$data['type']])->find();
        if ($dz_res){
            $is_dz = 1;
        }
        $data['is_dz'] = $is_dz;
        if (strpos($data['city'],'/')!==false){
            $data['city'] = explode('/',$data['city'])[1];
        }
        $is_gz = 0;
        $gz_res = Follow::where(['user_id'=>$this->auth->id,'df_id'=>$data['user_id']])->find();
        if ($gz_res){
            $is_gz = 1;
        }
        $data['is_gz'] = $is_gz;
        $data['createtime'] = date('Y-m-d',$data['createtime']);
        $data['jqt_name'] = "";
        if ($data['jqt_id']){
            $data['jqt_name'] = \app\common\model\fzly\attractions\Type::where(['id'=>$data['jqt_id']])->value('title');
        }
        $this->success('success', $data);

    }

    /*
     * 发布内容/修改内容
     */
    public function add(){
        $user_id = $this->auth->id;
        $type = $this->request->post('type')??1; //1=攻略,2=游记,3=美食,4=动态
        $fb_type = $this->request->post('fb_type')??1; //1=草稿,2=发布
        $title = $this->request->post('title'); //标题
        $content = $this->request->post('content',"",null); //内容
        $images = $this->request->post('images/a'); //详情图
        $image = $this->request->post('image'); //缩略图
        $mp_id = $this->request->post('mp_id')??0; //门票
        $type_id = $this->request->post('type_id'); //动态分类
        $id = $this->request->post('id'); //内容id
        $city = $this->request->header('city')??urlencode($this->config['city']);//城市 默认郑州
        $city   = urldecode($city);
        $area = Area::where('name','like',"%$city%")->whereOr('shortname','like',"%$city%")->find();
        $p = Area::get($area['pid']);
        $city = $p['name'] . '/' . $area['name'];
        if (!$title) {
            $this->error('缺少标题');
        }
        if ($type==4){
            if (!$type_id){
                $this->error("缺少动态分类");
            }
            if ($images){
                $images = implode(',',$images);
            }
            if ($id){
                $res = Trends::update([
                    'type_id' => $type_id,
                    'title' => $title,
                    'content' => $content,
                    'images' => $images,
                    'updatetime' => time(),
                ],['id' => $id]);
            }else{
                $res = Trends::create([
                    'type_id' => $type_id,
                    'user_id' => $user_id,
                    'title' => $title,
                    'content' => $content,
                    'status' => $fb_type,
                    'images' => $images,
                    'createtime' => time(),
                    'updatetime' => time(),
                ]);
            }

            if ($res){
                $this->success('发布成功');
            }else{
                $this->error('发布失败');
            }
        }else{
            if ($images){
                $images = implode(',',$images);
            }
            if (!$mp_id){
                $this->error("缺少景区id");
            }
            if (!$content){
                $this->error("缺少内容");
            }
//            if (!$image){
//                $this->error("缺少缩略图");
//            }
//            $mp = \app\common\model\fzly\admission\Admission::get($mp_id);
//            if (!$mp){
//                $this->error("门票不存在");
//            }
            if ($id){
                $res = \app\common\model\fzly\content\Content::update([
                    'jqt_id' => $mp_id,
                    'type' => $type,
                    'mp_id' => $mp_id,
                    'city' => $city,
                    'title' => $title,
                    'content' => $content,
                    'image' => $image,
                    'images' => $images,
                    'updatetime' => time(),
                ],['id' => $id]);
            }else{
                $res = \app\common\model\fzly\content\Content::create([
                    'jqt_id' => $mp_id,
                    'type' => $type,
                    'mp_id' => $mp_id,
                    'user_id' => $user_id,
                    'city' => $city,
                    'title' => $title,
                    'content' => $content,
                    'image' => $image,
                    'images' => $images,
                    'status' => $fb_type,
                    'view_nums' => 0,
                    'dz_nums' => 0,
                    'sc_nums' => 0,
                    'createtime' => time(),
                    'updatetime' => time(),
                ]);
            }

            if ($res){
                $this->success('发布成功');
            }else{
                $this->error('发布失败');
            }
        }
    }

    /*
     * 我的发布内容列表
     */
    public function drafts(){
        $user_id = $this->auth->id;
        $page   = $this->request->post('page')??1;  //页码
        $limit  = $this->request->post('limit')??10; //每页显示条数
        $type = $this->request->post('type')??1; //1=攻略,2=游记,3=美食,4=动态
        $status = $this->request->post('status'); //状态:1=草稿,2=未审核,3=审核通过,4=审核拒绝,5=已下架
        $where['user_id'] = ['=',$user_id];
        if ($status){
            $where['status'] = ['=',$status];
        }
        if($type == 4) {
            $data = Trends::where($where)->paginate(["page" => $page, "list_rows" => $limit])
                ->each(function ($item) {
                    $item->image = cdnurl($item->image, true);
                    $images = explode(',', $item->images);
                    foreach ($images as $k => &$v) {
                        $v = cdnurl($v, true);
                    }
                    $item->images = $images;
                });
        }else{
            $data = \app\common\model\fzly\content\Content::where($where)->paginate(["page" => $page, "list_rows" => $limit])
                ->each(function ($item) {
                    $item->image = cdnurl($item->image, true);
                });
        }
        $this->success('success', $data);
    }

    /*
     * 删除内容
     */
    public function delete(){
        $id = $this->request->post('id');
        if (!$id) {
            $this->error('缺少内容id');
        }
        $type = $this->request->post('type')??1; //1=攻略,2=游记,3=美食,4=动态
        if($type == 4) {
            $res = Trends::destroy($id);
            Dz::where(['con_id'=>$id,'type'=>4])->delete();
        }else{
            $res = \app\common\model\fzly\content\Content::destroy($id);
            Sc::where(['con_id'=>$id,'type'=>$type])->delete();
            Dz::where(['con_id'=>$id,'type'=>$type])->delete();
        }
        if ($res) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /*
     * 评论
     */
    public function comment(){
        $user_id = $this->auth->id;
        $type = $this->request->post('type')??1; //1=动态,2=攻略/游记/美食
        $id = $this->request->post("id");           //动态id
        $p_id = $this->request->post("p_id")??0; //上级id
        $u_id = $this->request->post("u_id")??0; //回复人id
        $content = $this->request->post("content"); //评论内容
        if (!$id){
            $this->error("缺少参数");
        }
        //查询信息是否存在
        if($type == 1) {
            $res = Trends::get($id);
            if (!$res){
                $this->error("不存在此动态");
            }
        }else{
            $res = \app\common\model\fzly\content\Content::get($id);
            if (!$res){
                $this->error("不存在此内容");
            }
        }


        //添加评论
        Comment::create([
            "user_id"=>$user_id,
            "type"=>$type,
            "p_id"=>$p_id,
            "u_id"=>$u_id,
            "con_id"=>$id,
            "content"=>$content,
            "createtime"=>time(),
            "updatetime"=>time(),
        ]);

        $res->setInc('common_nums');
        $res->save();
        $this->success("发布评论成功");

    }

    /*
     * 删除评论
     */
    public function comment_delete(){
        $user_id = $this->auth->id;
        $id = $this->request->post("id");           //评论id
        if (!$id){
            $this->error("缺少参数");
        }
        //查询评论信息是否存在
        $res = Comment::get($id);
        if (!$res){
            $this->error("不存在此评论信息");
        }
        if ($user_id != $res['user_id']){
            $this->error("无权删除");
        }
        //删除评论
        Comment::update(["is_del"=>1],["id"=>$id]);
        $this->success("删除评论成功");

    }



    /*
     * 获取评论列表
     */
    public function get_comment(){
        $type = $this->request->post('type')??1; //1=动态,2=攻略/游记/美食
        $id     = $this->request->post("id");        //动态id
        $page   = $this->request->post('page')??1;   //页码
        $limit  = $this->request->post('limit')??10; //每页显示条数
        $result = [];
        $data = $this->comment_lists($type,$id,0,$result,$page,$limit);
        $this->success("success",$data);
    }

    /*
     * 递归获取评论列表
     */
    public function comment_lists($type,$id,$p_id = 0,&$result = array(),$page = 1,$limit = 10){
        $arr = Comment::with(['user'])
            ->where(["type"=>$type])
            ->where(["con_id"=>$id,"p_id"=>$p_id])
            ->field("username,avatar")
            ->order("id","desc")
            ->paginate(["page"=>$page,"list_rows"=>$limit])
            ->each(function ($ite)use ($type){
                if ($type == 1){
                    $con = Trends::get($ite->con_id);
                }else{
                    $con = \app\common\model\fzly\content\Content::get($ite->con_id);
                }
                $is_zz = 0;
                if ($ite->user_id == $con->user_id){
                    $is_zz = 1;
                }
                $ite->is_zz = $is_zz;
                $ite->avatar = cdnurl($ite->avatar,true);

                $ite->createtime = fzly_format_dates($ite->createtime);
                $ite->chileren = Comment::where(["p_id"=>$ite->id,"type"=>$type])->order("id","asc")->limit(20)->field('id,content,con_id,user_id,p_id,u_id,is_del,createtime')->select()
                    ->each(function ($item)use ($type){
                        $u = U::where(["id"=>$item->user_id])->find();
                        $item->username = $u->username;
                        $item->avatar = cdnurl($u->avatar,true);
                        $item->username = U::where(["id"=>$item->user_id])->value("username");
                        $item->hf_username = U::where(["id"=>$item->u_id])->value("username");
                        if ($type == 1){
                            $con = Trends::get($item->con_id);
                        }else{
                            $con = \app\common\model\fzly\content\Content::get($item->con_id);
                        }
                        $is_zz = 0;
                        if ($item->user_id == $con->user_id){
                            $is_zz = 1;
                        }
                        $item->createtime = fzly_format_dates($item->createtime);
                        $item->is_zz = $is_zz;
                    });
                $ite->hidden(['user',"updatetime","p_id","u_id","is_del"]);
            });
        if (empty($arr)){
            return [];
        }
        $arr = $arr->toArray();
        return $arr;
    }


}
