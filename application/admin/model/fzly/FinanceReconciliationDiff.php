<?php

namespace app\admin\model\fzly;

use think\Model;


class FinanceReconciliationDiff extends Model
{

    

    

    // 表名
    protected $name = 'fzly_finance_reconciliation_diff';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'status_text'
    ];
    

    
    public function getStatusList()
    {
        return ['unhandled' => __('Unhandled'), 'handled' => __('Handled')];
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ?: ($data['status'] ?? '');
        $list = $this->getStatusList();
        return $list[$value] ?? '';
    }




}
