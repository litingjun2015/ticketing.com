<?php
namespace app\admin\model\fzly;

use think\Model;
// 引入利润明细模型（必须确保该模型也存在）
use app\admin\model\fzly\ProfitDetail;

class FinanceReconciliation extends Model
{
    // 表名（若表名与模型名规则一致，可省略；否则必须指定）
    protected $name = 'fzly_finance_reconciliation';

    // 自动写入时间戳（integer 表示存储为时间戳）
    protected $autoWriteTimestamp = 'integer';
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性：对账状态文本
    protected $append = ['reconciliation_status_text'];

    // 对账状态枚举
    public function getReconciliationStatusList()
    {
        return ['pending' => '待对账', 'completed' => '已完成', 'abnormal' => '异常'];
    }

    // 对账状态文本获取器
    public function getReconciliationStatusTextAttr($value, $data)
    {
        $list = $this->getReconciliationStatusList();
        return $list[$data['reconciliation_status']] ?? '';
    }

    // 关联利润核算明细表（核心：修复关联方法）
    public function profitDetails()
    {
        // hasMany(关联模型, 外键, 主键)
        return $this->hasMany(ProfitDetail::class, 'reconciliation_id', 'id');
    }
}