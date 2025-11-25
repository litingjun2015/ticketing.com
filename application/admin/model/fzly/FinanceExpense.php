<?php

namespace app\admin\model\fzly;

use think\Model;


class FinanceExpense extends Model
{

    

    

    // 表名
    protected $name = 'fzly_finance_expense';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'expense_type_text',
        'status_text'
    ];
    

    
    public function getExpenseTypeList()
    {
        return ['purchase' => __('Purchase'), 'ota_commission' => __('Ota_commission'), 'equipment_maintenance' => __('Equipment_maintenance'), 'salary' => __('Salary'), 'other' => __('Other')];
    }

    public function getStatusList()
    {
        return ['pending' => __('Pending'), 'confirmed' => __('Confirmed'), 'canceled' => __('Canceled')];
    }


    public function getExpenseTypeTextAttr($value, $data)
    {
        $value = $value ?: ($data['expense_type'] ?? '');
        $list = $this->getExpenseTypeList();
        return $list[$value] ?? '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ?: ($data['status'] ?? '');
        $list = $this->getStatusList();
        return $list[$value] ?? '';
    }




}
