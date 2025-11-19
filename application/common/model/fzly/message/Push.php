<?php

namespace app\common\model\fzly\message;

use think\Model;


class Push extends Model
{

    

    

    // 表名
    protected $name = 'fzly_message_push';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'message_type_text'
    ];
    

    
    public function getMessageTypeList()
    {
        return ['1' => __('Message_type 1'),'2' => __('Message_type 2')];
    }


    public function getMessageTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['message_type']) ? $data['message_type'] : '');
        $list = $this->getMessageTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
