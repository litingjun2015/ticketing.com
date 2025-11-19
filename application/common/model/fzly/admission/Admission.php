<?php

namespace app\common\model\fzly\admission;

use think\Model;
use traits\model\SoftDelete;

class Admission extends Model
{

    use SoftDelete;

    protected $resultSetType = 'collection';

    // 表名
    protected $name = 'fzly_admission';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [

    ];
    

    protected static function init()
    {
        self::afterInsert(function ($row) {
            $pk = $row->getPk();
            $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
        });
    }

    
    public function getFlagList()
    {
        return ['hot' => __('Flag hot'), 'recommend' => __('Flag recommend')];
    }

    public function getStatusList()
    {
        return ['normal' => __('Normal'), 'hidden' => __('Hidden')];
    }


    public function getFlagTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['flag']) ? $data['flag'] : '');
        $valueArr = explode(',', $value);
        $list = $this->getFlagList();
        return implode(',', array_intersect_key($list, array_flip($valueArr)));
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    protected function setFlagAttr($value)
    {
        return is_array($value) ? implode(',', $value) : $value;
    }


    public function type()
    {
        return $this->belongsTo('Type', 'type_ids', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    public function fl()
    {
        return $this->belongsTo('app\common\model\fzly\attractions\Type', 'type_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    public function attractions()
    {
        return $this->belongsTo('app\common\model\fzly\attractions\Attractions', 'attractions_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
