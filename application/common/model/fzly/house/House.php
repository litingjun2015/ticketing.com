<?php

namespace app\common\model\fzly\house;

use think\Model;
use traits\model\SoftDelete;

class House extends Model
{

    use SoftDelete;

    

    // 表名
    protected $name = 'fzly_house';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [

    ];
    

    protected static function init()
    {
        self::afterInsert(function ($row) {
            $pk = $row->getPk();
            $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
        });
    }

    
    public function getStayTypeList()
    {
        return ['1' => __('青年旅社'), '2' => __('公寓'), '3' => __('民宿'), '4' => __('度假酒店'), '5' => __('别墅'), '6' => __('主题酒店'), '7' => __('精选酒店')];
    }

    public function getGradeList()
    {
        return ['1' => __('经济'), '2' => __('舒适'), '3' => __('高档'), '4' => __('豪华')];
    }

    public function getFacilitiesList()
    {
        return ['1' => __('温泉'), '2' => __('厨房'), '3' => __('停车场'), '4' => __('洗衣房'), '5' => __('wifi')];
    }

    public function getStatusList()
    {
        return ['normal' => __('Normal'), 'hidden' => __('Hidden')];
    }


    public function getStayTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['stay_type']) ? $data['stay_type'] : '');
        $valueArr = explode(',', $value);
        $list = $this->getStayTypeList();
        return implode(',', array_intersect_key($list, array_flip($valueArr)));
    }


    public function getGradeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['grade']) ? $data['grade'] : '');
        $list = $this->getGradeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getFacilitiesTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['facilities']) ? $data['facilities'] : '');
        $valueArr = explode(',', $value);
        $list = $this->getFacilitiesList();
        return implode(',', array_intersect_key($list, array_flip($valueArr)));
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    protected function setStayTypeAttr($value)
    {
        return is_array($value) ? implode(',', $value) : $value;
    }

    protected function setFacilitiesAttr($value)
    {
        return is_array($value) ? implode(',', $value) : $value;
    }


    public function brand()
    {
        return $this->belongsTo('Brand', 'brand_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
