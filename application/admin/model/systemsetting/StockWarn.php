<?php
namespace app\admin\model\systemsetting;

use think\Model;

class StockWarn extends Model
{
    protected $name = 'system_setting_stock_warn';
    protected $pk = 'id';
    protected $autoWriteTimestamp = true;

    protected $validateRule = [
        'channel' => 'require|in:官网,OTA,窗口',
        'date_start' => 'require|date',
        'date_end' => 'require|date|after:date_start',
        'ticket_type' => 'require|max:30',
        'warn_value' => 'require|number|gt:0|lt:10000',
    ];

    protected $validateMsg = [
        'channel.require' => '渠道类型不能为空',
        'channel.in' => '渠道类型只能是官网、OTA或窗口',
        'date_start.require' => '生效开始日期不能为空',
        'date_start.date' => '生效开始日期格式不正确',
        'date_end.require' => '生效结束日期不能为空',
        'date_end.date' => '生效结束日期格式不正确',
        'date_end.after' => '生效结束日期必须在开始日期之后',
        'ticket_type.require' => '票种不能为空',
        'warn_value.require' => '预警阈值不能为空',
        'warn_value.number' => '预警阈值必须为数字',
        'warn_value.gt' => '预警阈值必须大于0',
        'warn_value.lt' => '预警阈值必须小于10000',
    ];

    public function checkUnique($data, $id = 0)
    {
        $where = [
            'channel' => $data['channel'],
            'date_start' => $data['date_start'],
            'date_end' => $data['date_end'],
            'ticket_type' => $data['ticket_type']
        ];
        if ($id) {
            $where['id'] = ['neq', $id];
        }
        $exist = $this->where($where)->find();
        return empty($exist);
    }
}