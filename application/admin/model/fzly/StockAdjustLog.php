<?php
namespace app\admin\model\fzly;

use think\Model;

class StockAdjustLog extends Model
{
    protected $name = 'stock_adjust_log';
    protected $pk = 'id';
    protected $autoWriteTimestamp = true;

    // 调整类型文本
    public function getAdjustTypeTextAttr($value, $data)
    {
        $type = [1 => '增加', 2 => '减少'];
        return $type[$data['adjust_type']];
    }
}