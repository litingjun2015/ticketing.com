<?php

namespace app\common\model\fzly\distribution;

use think\Model;


class Share extends Model
{

    

    

    // 表名
    protected $name = 'fzly_share';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];
    

    







    public function admin()
    {
        return $this->belongsTo('app\admin\model\Admin', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }


    public function log()
    {
        return $this->belongsTo('app\admin\model\admin\Log', 'p_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
