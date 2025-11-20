<?php
namespace app\admin\model\systemsetting;

use think\Model;

class OrderRule extends Model
{
    protected $name = 'system_setting_order_rule';
    protected $pk = 'id';
    protected $autoWriteTimestamp = true;

    protected $validateRule = [
        'pay_timeout' => 'require|number|between:5,120',
        'stock_release_interval' => 'require|number|between:1,30',
    ];

    protected $validateMsg = [
        'pay_timeout.require' => '支付超时时间不能为空',
        'pay_timeout.number' => '支付超时时间必须为数字',
        'pay_timeout.between' => '支付超时时间必须在5-120分钟之间',
        'stock_release_interval.require' => '库存释放间隔不能为空',
        'stock_release_interval.number' => '库存释放间隔必须为数字',
        'stock_release_interval.between' => '库存释放间隔必须在1-30分钟之间',
    ];
}