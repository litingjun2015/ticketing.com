<?php

namespace app\common\model\fzly\attractions;

use think\Model;


class Attractions extends Model
{

    

    

    // 表名
    protected $name = 'fzly_attractions';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'status_text'
    ];




    public function getStatusList()
    {
        return ['0' => __('未审核'), '1' => __('审核通过'), '2' => __('审核失败')];
    }



    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




    public function type()
    {
        return $this->belongsTo('Type', 'type_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    public function user()
    {
        return $this->belongsTo('app\common\model\User', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
