<?php

namespace app\admin\model\fzly;

use think\Model;


class Stock extends Model
{

    

    

    // 表名
    protected $name = 'fzly_stock';
    
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
        return ['offline' => __('Channel offline'), 'ota' => __('Channel ota'), 'mini_program' => __('Channel mini_program')];
    }

    public function getStatusList()
    {
        return ['0' => __('Status 0'), '1' => __('Status 1')];
    }

    /**
     * 定义与产品模型的关联（一对一/多对一）
     * 假设库存表通过 product_id 关联产品表的 id
     */
    public function product()
    {
        // 参数说明：
        // 1. 关联的产品模型类名（需根据实际命名空间调整）
        // 2. 库存表中的外键字段（通常是 product_id）
        // 3. 产品表中的主键字段（通常是 id）
        return $this->belongsTo('Product', 'product_id', 'id');
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
