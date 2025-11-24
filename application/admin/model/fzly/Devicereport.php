<?php

namespace app\admin\model\fzly;

use think\Model;


class Devicereport extends Model
{

    

    

    // 表名
    protected $name = 'fzly_device_report';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'status_text',
        'operation_time_text',
        'fault_time_text'
    ];
    

    
    public function getStatusList()
    {
        return ['normal' => __('Normal'), 'warning' => __('Warning'), 'error' => __('Error')];
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ?: ($data['status'] ?? '');
        $list = $this->getStatusList();
        return $list[$value] ?? '';
    }


    public function getOperationTimeTextAttr($value, $data)
    {
        $value = $value ?: ($data['operation_time'] ?? '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getFaultTimeTextAttr($value, $data)
    {
        $value = $value ?: ($data['fault_time'] ?? '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setOperationTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    protected function setFaultTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


}
