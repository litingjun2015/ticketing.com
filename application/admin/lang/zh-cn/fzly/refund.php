<?php

return [
    'Id'                    => '主键ID',
    'Refund_no'             => '退单号',
    'Order_id'              => '关联订单ID',
    'Order_no'              => '原订单号',
    'User_id'               => '用户ID',
    'Order_type'            => '订单类型(1:门票订单,2:导游订单)',
    'Refund_amount'         => '退款金额',
    'Fee_amount'            => '手续费金额',
    'Actual_amount'         => '实际退款金额',
    'Refund_reason'         => '退款原因',
    'Refund_type'           => '退款方式(1:原路返回)',
    'Status'                => '状态(1:申请中,2:已同意,3:已拒绝,4:已完成,5:失败)',
    'Rule_id'               => '适用规则ID',
    'Admin_id'              => '处理管理员ID',
    'Handle_time'           => '处理时间',
    'Handle_note'           => '处理备注',
    'Transaction_id'        => '支付平台交易号',
    'Refund_transaction_id' => '支付平台退款号',
    'Create_time'           => '创建时间',
    'Update_time'           => '更新时间'
];
