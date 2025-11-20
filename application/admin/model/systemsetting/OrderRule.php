<?php

namespace app\admin\model\systemsetting;

use think\Model;
use think\Validate;

class OrderRule extends Model
{
    protected $name = 'system_setting_order_rule';
    protected $pk = 'id';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    protected $dateFormat = 'int';

    // 验证规则
    protected $validateRule = [
        'pay_timeout' => 'require|integer|between:5,120',
        'stock_release_interval' => 'require|integer|between:1,30|lt:pay_timeout',
        'status' => 'require|in:0,1',
    ];

    // 验证提示
    protected $validateMsg = [
        'pay_timeout.between' => '支付超时时间需在5-120分钟之间',
        'stock_release_interval.between' => '库存释放间隔需在1-30分钟之间',
        'stock_release_interval.lt' => '库存释放间隔需小于支付超时时间',
    ];
}