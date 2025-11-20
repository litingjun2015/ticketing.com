<?php

namespace app\admin\model\systemsetting;

use think\Model;
use think\Validate;

class Basic extends Model
{
    // 表名
    protected $name = 'system_setting_basic';
    // 主键
    protected $pk = 'id';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    protected $dateFormat = 'int';

    // 验证规则
    protected $validateRule = [
        'scenic_name' => 'require|length:1,50|unique:system_setting_basic',
        'system_name' => 'require|length:1,100',
        'contact_phone' => 'require|regex:^1[3-9]\d{9}$',
        'address' => 'require|length:1,255',
        'copyright' => 'require|length:1,255',
        'icp' => 'length:0,50',
        'refresh_interval' => 'require|integer|between:1,60',
        'login_captcha' => 'require|in:0,1',
        'operation_log' => 'require|in:0,1',
        'default_lang' => 'require|in:zh-cn,en-us',
        'time_format' => 'require|length:1,50',
        'logo' => 'fileExt:png,jpg|fileSize:5242880', // 5MB
    ];

    // 验证提示
    protected $validateMsg = [
        'scenic_name.require' => '景区名称不能为空',
        'scenic_name.unique' => '景区名称已存在',
        'system_name.require' => '系统名称不能为空',
        'contact_phone.regex' => '请输入有效的11位手机号',
        'address.require' => '详细地址不能为空',
        'copyright.require' => '版权信息不能为空',
        'refresh_interval.between' => '刷新间隔必须在1-60秒之间',
        'login_captcha.in' => '登录验证码设置不正确',
        'operation_log.in' => '操作日志设置不正确',
        'default_lang.in' => '默认语言设置不正确',
        'logo.fileExt' => 'LOGO仅支持PNG/JPG格式',
        'logo.fileSize' => 'LOGO大小不能超过5MB',
    ];

    // 新增/编辑前验证
    public function checkData($data)
    {
        $validate = new Validate($this->validateRule, $this->validateMsg);
        return $validate->check($data) ? true : $validate->getError();
    }
}