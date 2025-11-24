<?php

namespace app\admin\model\fzly;

use think\Model;


class Reportschedule extends Model
{

    

    

    // 表名
    protected $name = 'fzly_report_schedule';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'report_type_text',
        'frequency_text',
        'send_time_text',
        'format_text',
        'status_text',
        'last_send_time_text'
    ];
    

    
    public function getReportTypeList()
    {
        return ['sales' => __('Sales'), 'stock' => __('Stock'), 'visitor' => __('Visitor'), 'device' => __('Device')];
    }

    public function getFrequencyList()
    {
        return ['daily' => __('Daily'), 'weekly' => __('Weekly'), 'monthly' => __('Monthly')];
    }

    public function getFormatList()
    {
        return ['excel' => __('Excel'), 'pdf' => __('Pdf')];
    }

    public function getStatusList()
    {
        return ['0' => __('Status 0'), '1' => __('Status 1')];
    }


    public function getReportTypeTextAttr($value, $data)
    {
        $value = $value ?: ($data['report_type'] ?? '');
        $list = $this->getReportTypeList();
        return $list[$value] ?? '';
    }


    public function getFrequencyTextAttr($value, $data)
    {
        $value = $value ?: ($data['frequency'] ?? '');
        $list = $this->getFrequencyList();
        return $list[$value] ?? '';
    }


    public function getSendTimeTextAttr($value, $data)
    {
        $value = $value ?: ($data['send_time'] ?? '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getFormatTextAttr($value, $data)
    {
        $value = $value ?: ($data['format'] ?? '');
        $list = $this->getFormatList();
        return $list[$value] ?? '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ?: ($data['status'] ?? '');
        $list = $this->getStatusList();
        return $list[$value] ?? '';
    }


    public function getLastSendTimeTextAttr($value, $data)
    {
        $value = $value ?: ($data['last_send_time'] ?? '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setSendTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    protected function setLastSendTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


}
