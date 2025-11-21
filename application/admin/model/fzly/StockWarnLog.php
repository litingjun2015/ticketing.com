<?php
namespace app\admin\model\fzly;

use think\Model;

class StockWarnLog extends Model
{
    protected $name = 'stock_warn_log';
    protected $pk = 'id';
    protected $autoWriteTimestamp = false;

    // 状态文本
    public function getStatusTextAttr($value, $data)
    {
        $status = [0 => '未处理', 1 => '已处理'];
        return $status[$data['status']];
    }

    // 处理预警
    public function handleWarn($id, $userId, $userName)
    {
        return $this->where('id', $id)->update([
            'status' => 1,
            'process_time' => time(),
            'process_user_id' => $userId,
            'process_user_name' => $userName
        ]);
    }
}