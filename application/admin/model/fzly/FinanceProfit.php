<?php

namespace app\admin\model\fzly;

use think\Model;


class FinanceProfit extends Model
{

    

    

    // 表名
    protected $name = 'fzly_finance_profit';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'period_type_text'
    ];
    

    
    public function getPeriodTypeList()
    {
        return ['daily' => __('Daily'), 'monthly' => __('Monthly'), 'yearly' => __('Yearly')];
    }


    public function getPeriodTypeTextAttr($value, $data)
    {
        $value = $value ?: ($data['period_type'] ?? '');
        $list = $this->getPeriodTypeList();
        return $list[$value] ?? '';
    }




}
