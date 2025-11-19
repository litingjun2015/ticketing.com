<?php

namespace app\common\model\fzly\app;

use think\Model;


class Url extends Model
{

    

    

    // 表名
    protected $name = 'fzly_app_url';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];
    

    







    public function app()
    {
        return $this->belongsTo('app\common\model\fzly\App', 'type_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
