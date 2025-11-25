<?php

namespace app\admin\model\fzly\finance;

use think\Model;


class Reconciliation extends Model
{

    

    

    // 表名
    protected $name = 'fzly_finance_reconciliation';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'reconciliation_type_text',
        'status_text'
    ];
    

    
    public function getReconciliationTypeList()
    {
        return ['ota' => __('Ota'), 'supplier' => __('Supplier'), 'bank' => __('Bank')];
    }

    public function getStatusList()
    {
        return ['pending' => __('Pending'), 'reconciling' => __('Reconciling'), 'completed' => __('Completed'), 'abnormal' => __('Abnormal')];
    }


    public function getReconciliationTypeTextAttr($value, $data)
    {
        $value = $value ?: ($data['reconciliation_type'] ?? '');
        $list = $this->getReconciliationTypeList();
        return $list[$value] ?? '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ?: ($data['status'] ?? '');
        $list = $this->getStatusList();
        return $list[$value] ?? '';
    }




}
