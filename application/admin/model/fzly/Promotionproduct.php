<?php

namespace app\admin\model\fzly;

use think\Model;


class Promotionproduct extends Model
{

    

    

    // 表名
    protected $name = 'fzly_promotion_product';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'product_type_text'
    ];
    

    
    public function getProductTypeList()
    {
        return ['ticket' => __('Ticket'), 'goods' => __('Goods')];
    }


    public function getProductTypeTextAttr($value, $data)
    {
        $value = $value ?: ($data['product_type'] ?? '');
        $list = $this->getProductTypeList();
        return $list[$value] ?? '';
    }




}
