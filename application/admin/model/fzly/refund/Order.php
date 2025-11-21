<?php

namespace app\admin\model\fzly\refund;

use think\Model;
use app\admin\model\User;
use app\common\model\fzly\order\Order as OrderModel;

class Order extends Model
{
    protected $name = 'fzly_refund_order';
    protected $pk = 'id';
    protected $autoWriteTimestamp = true;

    // 驼峰命名：originalOrder（与控制器保持一致）
    public function originalOrder()
    {
        // 确保 OrderModel 已正确引入，外键 order_id 对应原订单表的 id
        return $this->belongsTo(OrderModel::class, 'order_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    /**
     * 生成退单号
     */
    public function generateRefundNo()
    {
        return 'RF' . date('YmdHis') . mt_rand(1000, 9999);
    }

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
     * 获取退款方式列表
     */
    public function getRefundTypeList()
    {
        return [
            1 => __('Original payment method'),
        ];
    }

    /**
     * 获取状态列表
     */
    public function getStatusList()
    {
        return [
            1 => __('Pending'),
            2 => __('Approved'),
            3 => __('Rejected'),
            4 => __('Completed'),
            5 => __('Failed'),
        ];
    }

    /**
     * 关联用户
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    /**
     * 关联原订单
     */
    public function order()
    {
        return $this->belongsTo(OrderModel::class, 'order_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    /**
     * 关联退款规则
     */
    public function rule()
    {
        return $this->belongsTo(Rule::class, 'rule_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}