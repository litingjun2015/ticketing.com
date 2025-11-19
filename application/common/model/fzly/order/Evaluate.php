<?php

namespace app\common\model\fzly\order;

use think\Model;


class Evaluate extends Model
{
    protected $resultSetType = 'collection';

    // 表名
    protected $name = 'fzly_order_evaluate';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

}
