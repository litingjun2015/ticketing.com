<?php

namespace app\admin\model\fzly;

use think\Model;


class PurchaseOrder extends Model
{

    

    

    // 表名
    protected $name = 'fzly_purchase_order';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'purchase_time_text',
        'arrival_time_text',
        'status_text'
    ];
    

    
    public function getStatusList()
    {
        return ['pending' => __('Pending'), 'received' => __('Received'), 'cancelled' => __('Cancelled')];
    }


    public function getPurchaseTimeTextAttr($value, $data)
    {
        $value = $value ?: ($data['purchase_time'] ?? '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getArrivalTimeTextAttr($value, $data)
    {
        $value = $value ?: ($data['arrival_time'] ?? '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ?: ($data['status'] ?? '');
        $list = $this->getStatusList();
        return $list[$value] ?? '';
    }

    protected function setPurchaseTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    protected function setArrivalTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


}
