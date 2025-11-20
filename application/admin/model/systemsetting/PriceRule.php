<?php

namespace app\admin\model\systemsetting;

use think\Model;
use think\Validate;

class PriceRule extends Model
{
    protected $name = 'system_setting_price_rule';
    protected $pk = 'id';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    protected $dateFormat = 'int';

    // 验证规则
    protected $validateRule = [
        'holiday_premium_switch' => 'require|in:0,1',
        'holiday_premium_rate' => 'require|integer|between:100,200',
        'group_discount_base' => 'require|integer|between:50,99',
        'apply_ticket_types' => 'require|array',
        'holiday_dates' => 'array',
        'status' => 'require|in:0,1',
    ];

    // 验证提示
    protected $validateMsg = [
        'holiday_premium_rate.between' => '溢价比例需在100%-200%之间',
        'group_discount_base.between' => '团体票折扣基准需在50%-99%之间',
        'apply_ticket_types.require' => '请选择适用票种',
    ];

    // 启用溢价时必须绑定节假日日期
    public function checkHolidayDate($data)
    {
        if ($data['holiday_premium_switch'] == 1 && (empty($data['holiday_dates']) || !is_array($data['holiday_dates']))) {
            return '启用节假日溢价需绑定具体日期';
        }
        return true;
    }
}