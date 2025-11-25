<?php

namespace app\admin\model\fzly;

use think\Model;


class LoginLog extends Model
{

    

    

    // 表名
    protected $name = 'fzly_login_log';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'logintime_text'
    ];
    

    



    public function getLogintimeTextAttr($value, $data)
    {
        $value = $value ?: ($data['logintime'] ?? '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setLogintimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


}
