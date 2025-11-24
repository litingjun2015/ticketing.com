<?php

namespace app\admin\model\fzly;

use think\Model;


class Datasynclog extends Model
{

    

    

    // 表名
    protected $name = 'fzly_data_sync_log';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'sync_time_text',
        'status_text'
    ];
    

    
    public function getStatusList()
    {
        return ['success' => __('Success'), 'fail' => __('Fail')];
    }


    public function getSyncTimeTextAttr($value, $data)
    {
        $value = $value ?: ($data['sync_time'] ?? '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ?: ($data['status'] ?? '');
        $list = $this->getStatusList();
        return $list[$value] ?? '';
    }

    protected function setSyncTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


}
