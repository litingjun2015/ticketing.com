<?php

namespace app\admin\model\fzly;

use think\Model;


class StockCheck extends Model
{

    

    

    // 表名
    protected $name = 'fzly_stock_check';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'check_time_text',
        'status_text'
    ];
    

    
    public function getStatusList()
    {
        return ['pending' => __('Pending'), 'completed' => __('Completed')];
    }


    public function getCheckTimeTextAttr($value, $data)
    {
        $value = $value ?: ($data['check_time'] ?? '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ?: ($data['status'] ?? '');
        $list = $this->getStatusList();
        return $list[$value] ?? '';
    }

    protected function setCheckTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


}
