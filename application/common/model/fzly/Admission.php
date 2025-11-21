<?php

namespace app\common\model\fzly;

use think\Model;

class Admission extends Model
{
    // 表名
    protected $name = 'fzly_admission';

    protected $validateRule = [
        // 其他规则...
        'price' => 'require|float|>=:0.01',
        'use_rules' => 'length:0,2000',
        'applicable_people' => 'require',
    ];

    protected $validateMsg = [
        // 其他提示...
        'price.require' => '价格不能为空',
        'price.float' => '价格必须为数字',
        'price.>=:0.01' => '价格不能小于0.01',
        'applicable_people.require' => '请选择适用人群',
    ];
}
