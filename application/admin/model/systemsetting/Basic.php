<?php
namespace app\admin\model\systemsetting;

use think\Model;

class Basic extends Model
{
    protected $name = 'system_setting_basic';
    protected $pk = 'id';
    protected $autoWriteTimestamp = true;

    protected $validateRule = [
        'scenic_name' => 'require|max:50',
        'contact_phone' => 'require|mobile',
        'address' => 'require|max:255',
        'refresh_interval' => 'require|number|between:1,60',
    ];

    protected $validateMsg = [
        'scenic_name.require' => '景区名称不能为空',
        'contact_phone.require' => '联系电话不能为空',
        'contact_phone.mobile' => '联系电话格式不正确',
        'address.require' => '地址不能为空',
        'refresh_interval.require' => '数据刷新间隔不能为空',
        'refresh_interval.number' => '数据刷新间隔必须为数字',
        'refresh_interval.between' => '数据刷新间隔必须在1-60秒之间',
    ];

    // 允许批量赋值的字段
    protected $fillable = [
        'scenic_name',
        'system_name',
        'logo',
        'contact_phone',
        'address',
        'icp',
        'refresh_interval',
        'login_captcha',
        'operation_log',
        'default_lang',
        'time_format'
    ];

    public function checkData($data)
    {
        $validate = new \think\Validate($this->validateRule, $this->validateMsg);
        if (!$validate->check($data)) {
            return $validate->getError();
        }
        return true;
    }
}