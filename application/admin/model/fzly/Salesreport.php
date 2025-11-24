<?php

namespace app\admin\model\fzly;

use think\Model;


class Salesreport extends Model
{

    

    

    // 表名
    protected $name = 'fzly_sales_report';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'channel_text'
    ];
    

    
    public function getChannelList()
    {
        return ['offline' => __('Offline'), 'ota' => __('Ota'), 'mini_program' => __('Mini_program'), 'official' => __('Official')];
    }


    public function getChannelTextAttr($value, $data)
    {
        $value = $value ?: ($data['channel'] ?? '');
        $list = $this->getChannelList();
        return $list[$value] ?? '';
    }




}
