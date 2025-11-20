<?php
namespace app\admin\model\systemsetting;

use think\Model;

class PriceRule extends Model
{
    protected $name = 'system_setting_price_rule';
    protected $pk = 'id';
    protected $autoWriteTimestamp = true;

    protected $validateRule = [
        'holiday_premium_rate' => 'require|number|between:100,200',
        'group_discount_base' => 'require|number|between:50,99',
    ];

    protected $validateMsg = [
        'holiday_premium_rate.require' => '节假日溢价比例不能为空',
        'holiday_premium_rate.number' => '节假日溢价比例必须为数字',
        'holiday_premium_rate.between' => '节假日溢价比例必须在100%-200%之间',
        'group_discount_base.require' => '团体票折扣基准不能为空',
        'group_discount_base.number' => '团体票折扣基准必须为数字',
        'group_discount_base.between' => '团体票折扣基准必须在50%-99%之间',
    ];

    public function checkHolidayDate($data)
    {
        if ($data['holiday_premium_switch'] && empty($data['holiday_dates'])) {
            return '启用节假日溢价时必须设置节假日日期';
        }
        return true;
    }
}