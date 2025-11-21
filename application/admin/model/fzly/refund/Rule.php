<?php

namespace app\admin\model\fzly\refund;

use think\Model;

class Rule extends Model
{
    protected $name = 'fzly_refund_rule';
    protected $pk = 'id';
    protected $autoWriteTimestamp = true;

    protected $validateRule = [
        'order_type' => 'require|in:1,2',
        'time_range' => 'require|number|gt:0',
        'fee_rate' => 'require|number|between:0,100',
        'min_fee' => 'require|float|egt:0',
        'max_fee' => 'require|float|gt:min_fee',
        'status' => 'require|in:0,1',
    ];

    protected $validateMsg = [
        'order_type.require' => '订单类型必须选择',
        'order_type.in' => '订单类型值不正确',
        'time_range.require' => '时间范围必须填写',
        'time_range.number' => '时间范围必须是数字',
        'time_range.gt' => '时间范围必须大于0',
        'fee_rate.require' => '手续费比例必须填写',
        'fee_rate.number' => '手续费比例必须是数字',
        'fee_rate.between' => '手续费比例必须在0-100之间',
        'min_fee.require' => '最低手续费必须填写',
        'min_fee.float' => '最低手续费必须是数字',
        'min_fee.egt' => '最低手续费不能小于0',
        'max_fee.require' => '最高手续费必须填写',
        'max_fee.float' => '最高手续费必须是数字',
        'max_fee.gt' => '最高手续费必须大于最低手续费',
        'status.require' => '状态必须选择',
        'status.in' => '状态值不正确',
    ];

    /**
     * 获取订单类型列表
     */
    public function getOrderTypeList()
    {
        return [
            1 => __('Order type 1'),
            2 => __('Order type 2'),
        ];
    }

    /**
     * 获取状态列表
     */
    public function getStatusList()
    {
        return [
            0 => __('Disabled'),
            1 => __('Enabled'),
        ];
    }
}