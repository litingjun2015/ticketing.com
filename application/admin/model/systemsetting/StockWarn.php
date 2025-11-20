<?php

namespace app\admin\model\systemsetting;

use think\Model;
use think\Validate;

class StockWarn extends Model
{
    protected $name = 'system_setting_stock_warn';
    protected $pk = 'id';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    protected $dateFormat = 'int';

    // 验证规则
    protected $validateRule = [
        'channel' => 'require|in:官网,OTA,窗口',
        'date_start' => 'require|date',
        'date_end' => 'require|date|after:date_start',
        'ticket_type' => 'require|length:1,30',
        'warn_value' => 'require|integer|gt:0',
        'receivers' => 'require|array',
    ];

    // 验证提示
    protected $validateMsg = [
        'channel.in' => '渠道类型仅支持官网/OTA/窗口',
        'date_end.after' => '结束日期需晚于开始日期',
        'warn_value.gt' => '预警阈值必须为正整数',
        'receivers.require' => '请选择通知接收人',
    ];

    // 检查渠道+日期+票种唯一性
    public function checkUnique($data, $id = 0)
    {
        $where = [
            'channel' => $data['channel'],
            'ticket_type' => $data['ticket_type'],
            'date_start' => $data['date_start'],
            'date_end' => $data['date_end'],
        ];
        if ($id > 0) $where['id'] = ['neq', $id];
        return $this->where($where)->count() === 0;
    }
}