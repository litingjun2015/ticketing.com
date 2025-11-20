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
        'contact_phone' => 'require|regex:^1[3-9]\d{9}$',
        'address' => 'require|length:1,255',
        'logo' => 'fileExt:png,jpg|fileSize:5242880', // 5MB
    ];

    // 验证提示
    protected $validateMsg = [
        'scenic_name.require' => '景区名称不能为空',
        'scenic_name.unique' => '景区名称已存在',
        'contact_phone.regex' => '请输入有效的11位手机号',
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