<?php

namespace app\admin\model\fzly;

use think\Model;

class ProfitDetail extends Model
{
    // 表名
    protected $name = 'fzly_profit_detail';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = false;
}