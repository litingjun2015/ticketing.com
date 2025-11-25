<?php

namespace app\admin\model\fzly;

use think\Model;

class StockValuation extends Model
{
    // 表名
    protected $name = 'fzly_stock_valuation';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'valuation_status_text'
    ];

    public function getValuationStatusList()
    {
        return ['normal' => __('Normal'), 'warning' => __('Warning'), 'expired' => __('Expired')];
    }

    public function getValuationStatusTextAttr($value, $data)
    {
        $value = $value ?: ($data['valuation_status'] ?? '');
        $list = $this->getValuationStatusList();
        return $list[$value] ?? '';
    }
}