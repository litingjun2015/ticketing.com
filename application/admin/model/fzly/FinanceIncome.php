<?php

namespace app\admin\model\fzly;

use think\Model;


class FinanceIncome extends Model
{

    

    

    // 表名
    protected $name = 'fzly_finance_income';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'income_type_text',
        'channel_text',
        'status_text'
    ];
    

    
    public function getIncomeTypeList()
    {
        return ['ticket' => __('Ticket'), 'product' => __('Product'), 'food' => __('Food'), 'timed_service' => __('Timed_service'), 'parking' => __('Parking')];
    }

    public function getChannelList()
    {
        return ['offline' => __('Offline'), 'ota' => __('Ota'), 'mini_program' => __('Mini_program'), 'official' => __('Official'), 'other' => __('Other')];
    }

    public function getStatusList()
    {
        return ['pending' => __('Pending'), 'confirmed' => __('Confirmed'), 'refunded' => __('Refunded')];
    }


    public function getIncomeTypeTextAttr($value, $data)
    {
        $value = $value ?: ($data['income_type'] ?? '');
        $list = $this->getIncomeTypeList();
        return $list[$value] ?? '';
    }


    public function getChannelTextAttr($value, $data)
    {
        $value = $value ?: ($data['channel'] ?? '');
        $list = $this->getChannelList();
        return $list[$value] ?? '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ?: ($data['status'] ?? '');
        $list = $this->getStatusList();
        return $list[$value] ?? '';
    }




}
