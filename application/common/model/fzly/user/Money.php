<?php

namespace app\common\model\fzly\user;

use think\Model;


class Money extends Model
{

    protected $resultSetType = 'collection';

    // 表名
    protected $name = 'fzly_guide_money_log';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];
    

}
