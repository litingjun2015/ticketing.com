<?php

namespace app\admin\model\fzly;

use think\Model;


class SalesOrder extends Model
{

    

    

    // 表名
    protected $name = 'fzly_sales_order';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'channel_text',
        'status_text'
    ];
    

    
    public function getChannelList()
    {
        return ['ticket_window' => __('Ticket_window'), 'restaurant' => __('Restaurant'), 'online' => __('Online')];
    }

    public function getStatusList()
    {
        return ['pending' => __('Pending'), 'completed' => __('Completed'), 'cancelled' => __('Cancelled')];
    }


    public function getChannelTextAttr($value, $data)
    {
        $value = $value ?: ($data['channel'] ?? '');
        $list = $this->getChannelList();
        return $list[$value] ?? '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ?: ($data['status'] ?? '');
        $list = $this->getStatusList();
        return $list[$value] ?? '';
    }




}
