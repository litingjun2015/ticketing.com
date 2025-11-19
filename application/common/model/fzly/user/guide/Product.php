<?php

namespace app\common\model\fzly\user\guide;

use think\Model;


class Product extends Model
{



    protected $resultSetType = 'collection';

    // 表名
    protected $name = 'fzly_guide_product';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];
    

    
    public function getModelList()
    {
        return ['1' => __('舒适五座'), '2' => __('商务七座'), '3' => __('中 大巴车'), '4' => __('暂无')];
    }

    public function getDurationList()
    {
        return ['1' => __('8小时'), '2' => __('4小时'), '3' => __('2小时'), '4' => __('1小时'), '5' => __('暂无')];
    }

    public function getStatusList()
    {
        return ['1' => __('Status 1'), '2' => __('Status 2'), '3' => __('Status 3'), '4' => __('Status 4')];
    }


    public function getModelTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['model']) ? $data['model'] : '');
        $list = $this->getModelList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getDurationTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['duration']) ? $data['duration'] : '');
        $list = $this->getDurationList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




    public function type()
    {
        return $this->belongsTo('app\common\model\fzly\guide\Type', 'type_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }


    public function user()
    {
        return $this->belongsTo('app\common\model\User', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
