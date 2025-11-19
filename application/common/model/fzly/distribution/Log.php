<?php

namespace app\common\model\fzly\distribution;

use think\Model;


class Log extends Model
{

    

    

    // 表名
    protected $name = 'fzly_distribution_log';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';
    protected $resultSetType = 'collection';


    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];



    public function getPayTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['pay_time']) ? $data['pay_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setPayTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


    public function user()
    {
        return $this->belongsTo('app\admin\model\User', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    public function orders()
    {
        return $this->belongsTo('app\common\model\fzly\order\Order', 'order_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
