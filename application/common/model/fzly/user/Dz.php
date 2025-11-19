<?php

namespace app\common\model\fzly\user;

use think\Model;


class Dz extends Model
{

    protected $resultSetType = 'collection';

    

    // 表名
    protected $name = 'fzly_user_dz';
    
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
