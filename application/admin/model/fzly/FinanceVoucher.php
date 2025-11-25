<?php

namespace app\admin\model\fzly;

use think\Model;


class FinanceVoucher extends Model
{

    

    

    // 表名
    protected $name = 'fzly_finance_voucher';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'voucher_type_text',
        'status_text'
    ];
    

    
    public function getVoucherTypeList()
    {
        return ['invoice' => __('Invoice'), 'receipt' => __('Receipt'), 'contract' => __('Contract')];
    }

    public function getStatusList()
    {
        return ['valid' => __('Valid'), 'invalid' => __('Invalid')];
    }


    public function getVoucherTypeTextAttr($value, $data)
    {
        $value = $value ?: ($data['voucher_type'] ?? '');
        $list = $this->getVoucherTypeList();
        return $list[$value] ?? '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ?: ($data['status'] ?? '');
        $list = $this->getStatusList();
        return $list[$value] ?? '';
    }




}
