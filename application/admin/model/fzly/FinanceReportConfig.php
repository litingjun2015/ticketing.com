<?php

namespace app\admin\model\fzly;

use think\Model;


class FinanceReportConfig extends Model
{

    

    

    // 表名
    protected $name = 'fzly_finance_report_config';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'is_public_text',
        'status_text'
    ];
    

    
    public function getIsPublicList()
    {
        return ['0' => __('Is_public 0'), '1' => __('Is_public 1')];
    }

    public function getStatusList()
    {
        return ['0' => __('Status 0'), '1' => __('Status 1')];
    }


    public function getIsPublicTextAttr($value, $data)
    {
        $value = $value ?: ($data['is_public'] ?? '');
        $list = $this->getIsPublicList();
        return $list[$value] ?? '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ?: ($data['status'] ?? '');
        $list = $this->getStatusList();
        return $list[$value] ?? '';
    }




}
