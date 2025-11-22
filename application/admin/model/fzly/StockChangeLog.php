<?php

namespace app\admin\model\fzly;

use think\Model;


class StockChangeLog extends Model
{

    

    

    // 表名
    protected $name = 'fzly_stock_change_log';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'type_text'
    ];
    

    
    public function getTypeList()
    {
        return ['purchase' => __('Purchase'), 'sale' => __('Sale'), 'adjust' => __('Adjust'), 'check' => __('Check')];
    }


    public function getTypeTextAttr($value, $data)
    {
        $value = $value ?: ($data['type'] ?? '');
        $list = $this->getTypeList();
        return $list[$value] ?? '';
    }




}
