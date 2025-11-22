<?php

return [
    'Id'              => '主键ID',
    'Product_id'      => '商品ID',
    'Type'            => '变动类型：purchase=采购入库,sale=销售出库,adjust=调整,check=盘点',
    'Related_id'      => '关联ID（订单ID或盘点ID等）',
    'Quantity'        => '变动数量（正数为增加,负数为减少）',
    'Before_quantity' => '变动前库存',
    'After_quantity'  => '变动后库存',
    'Remark'          => '备注',
    'Operate_id'      => '操作人ID',
    'Operate_name'    => '操作人姓名',
    'Createtime'      => '创建时间'
];
