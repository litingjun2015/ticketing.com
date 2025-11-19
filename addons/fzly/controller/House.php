<?php

namespace addons\fzly\controller;

use addons\epay\library\Service;
use app\common\controller\Api;
use app\common\model\fzly\coupon\Receive;
use app\common\model\fzly\distribution\Log as Dislog;
use app\common\model\fzly\house\Brand;
use app\common\model\fzly\house\Calendar;
use app\common\model\fzly\house\Housemlog;
use app\common\model\fzly\house\Houseuser;
use app\common\model\fzly\house\OrderCalendar;
use app\common\model\fzly\house\Room;
use app\common\model\fzly\house\Roomconfig;
use app\common\model\fzly\message\Push;
use app\common\model\fzly\order\Houseorder;
use DateInterval;
use DatePeriod;
use DateTime;
use think\Db;
use think\Exception;
use think\Hook;
use think\Log;
use think\Queue;
use think\Request;

class House extends Api
{

    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];
    protected $config = [];
    protected $model = '';

    public function __construct(Request $request = null)
    {
        $this->config = get_addon_config("fzly");
        $this->model = new \app\common\model\fzly\house\House();
        parent::__construct($request);
    }

    /*
     * 住宿类型列表
     */
    public function type_list()
    {
        $data = $this->model->getStayTypeList();
        $new = [];
        foreach ($data as $key => $value) {
            $new[] = ['id' => $key, 'title' => $value];
        }
        $this->success('获取成功', $new);
    }

    /*
     * 设施列表
     */
    public function facility_list(){
        $data = $this->model->getFacilitiesList();
        $new = [];
        foreach ($data as $key => $value) {
            $new[] = ['id' => $key, 'title' => $value];
        }
        $this->success('获取成功', $new);
    }

    /*
     * 品牌列表
     */
    public function brand_list(){
        $data = Brand::where('switch', 1)->field('id,title')->order('weigh desc')->select();
        $this->success('获取成功', $data);
    }

    /*
     * 档次列表
     */
    public function level_list(){
        $data = $this->model->getGradeList();
        $new = [];
        foreach ($data as $key => $value) {
            $new[] = ['id' => $key, 'title' => $value];
        }
        $this->success('获取成功', $new);
    }

    /*
     * 酒店列表
     */
    public function hotel_list(){
        $city = $this->request->header('city')??urlencode($this->config['city']);//城市 默认郑州
        $lon = $this->request->header("lon")??'113.663221';
        $lat = $this->request->header("lat")??'34.7568711';
        $sort   = $this->request->post('sort')??1;  //排序方式 1:综合  4：距离最短
        $page   = $this->request->post('page')??1;  //页码
        $limit  = $this->request->post('limit')??10; //每页显示条数
        $search  = $this->request->post('search')??""; //搜索条件
        $type_id  = $this->request->post('type_id/a')??""; //住宿类型
        $facility_id  = $this->request->post('facility_id/a')??""; //设施
        $brand_id  = $this->request->post('brand_id/a')??""; //品牌
        $level_id  = $this->request->post('level_id/a')??""; //档次
        $city   = urldecode($city);
        $where = [];
        $where['status'] = ['=', 'normal'];
        if($search){
            $where['title'] = ['like',"%$search%"];
        }
        if ($brand_id){
            $where['brand_id'] = ['in',$brand_id];
        }
        if ($level_id){
            $where['grade'] = ['in',$level_id];
        }
        if($type_id){
            $t_where = '';
            $count = count($type_id);
            foreach ($type_id as $k => $v) {
                if($k==$count-1){
                    $t_where.= "FIND_IN_SET($v,stay_type)";
                }else {
                    $t_where.= "FIND_IN_SET($v,stay_type) OR ";
                }
            }
        }else{
            $t_where = "1=1";
        }
        if($facility_id){
            $f_where = '';
            $count = count($facility_id);
            foreach ($facility_id as $k => $v) {
                if($k==$count-1){
                    $f_where.= "FIND_IN_SET($v,facilities)";
                }else {
                    $f_where.= "FIND_IN_SET($v,facilities) OR ";
                }
            }
        }else{
            $f_where = "1=1";
        }

        if ($sort==1){
            $order = 'weigh desc,id desc';
        }else{
            $order = 'distance asc';
        }

        $data = \app\common\model\fzly\house\House::where($where)->where($t_where)->where($f_where)
            ->where('city','like',"%$city%")
            ->field('id,title,city,image,address,'.fzly_getDistanceBuilder($lat, $lon))->order($order)->paginate(["page"=>$page,"list_rows"=>$limit])
            ->each(function ($item){
                $item->image = cdnurl($item->image,true);
            });
        $this->success('获取成功', $data);

    }

    /*
     * 酒店详情
     */
    public function house_detail()
    {
        $id = $this->request->post('id');
        $lon = $this->request->header("lon")??'113.663221';
        $lat = $this->request->header("lat")??'34.7568711';
        $data = \app\common\model\fzly\house\House::where(['id' => $id])->find();
        if (!$data) {
            $this->error('酒店不存在');
        }
        if ($data->status != 'normal'){
            $this->error('酒店已下架');
        }
        if ($data->images){
            $images = explode(',', $data->images);
            foreach ($images as $k => &$v) {
                $v = cdnurl($v, true);
            }
            $data->images = $images;
        }

        if ($data->multiplejson_yd){
            $multiplejson_yd = json_decode($data->multiplejson_yd, true);
            foreach ($multiplejson_yd as $k => &$yv) {
                if ($yv['icon']){
                    $yv['icon'] = cdnurl($yv['icon'], true);
                }
            }
            $data->multiplejson_yd = $multiplejson_yd;
        }else{
            $data->multiplejson_yd = [];
        }

        if ($data->multiplejson_md){
            $multiplejson_md = json_decode($data->multiplejson_md, true);
            foreach ($multiplejson_md as $k => &$yv) {
                if ($yv['icon']){
                    $yv['icon'] = cdnurl($yv['icon'], true);
                }
            }
            $data->multiplejson_md = $multiplejson_md;
        }else{
            $data->multiplejson_md = [];
        }

        $data->distance = get_distance([$lon,$lat],[$data->lon,$data->lat],false);

        $data->hidden(['image','brand_id','stay_type','grade','facilities']);

        $this->success('success', $data);
    }

}
