<?php
namespace app\admin\model\fzly;

use think\Model;
use think\Db;
use think\Log;

class Stock extends Model
{
    protected $name = 'stock';
    protected $pk = 'id';
    protected $autoWriteTimestamp = true;

    // 状态文本
    public function getStatusTextAttr($value, $data)
    {
        $status = [0 => '禁用', 1 => '启用'];
        return $status[$data['status']];
    }

    // 渠道文本
    public function getChannelTextAttr($value, $data)
    {
        $channel = ['官网' => '官网', 'OTA' => 'OTA', '窗口' => '窗口'];
        return $channel[$data['channel']] ?? $data['channel'];
    }

    // 检查库存是否低于预警值
    public function checkWarnStatus()
    {
        $warnModel = new \app\admin\model\systemsetting\StockWarn();

        // 获取所有库存记录
        $stocks = $this->where('status', 1)->select();

        foreach ($stocks as $stock) {
            // 查询对应预警配置
            $warnConfig = $warnModel->where([
                'channel' => $stock['channel'],
                'ticket_type' => $stock['ticket_type'],
                'date_start' => ['elt', $stock['date']],
                'date_end' => ['egt', $stock['date']]
            ])->find();

            if ($warnConfig && $stock['available_stock'] <= $warnConfig['warn_value']) {
                // 检查是否已存在未处理的预警记录
                $warnLog = Db::name('stock_warn_log')->where([
                    'stock_id' => $stock['id'],
                    'status' => 0
                ])->find();

                if (!$warnLog) {
                    // 创建预警记录
                    $warnLogId = Db::name('stock_warn_log')->insertGetId([
                        'stock_id' => $stock['id'],
                        'ticket_type' => $stock['ticket_type'],
                        'channel' => $stock['channel'],
                        'date' => $stock['date'],
                        'current_stock' => $stock['available_stock'],
                        'warn_value' => $warnConfig['warn_value'],
                        'warn_time' => time(),
                        'status' => 0
                    ]);

                    // 触发提醒（实际项目中可在这里发送短信、邮件等）
                    $this->sendWarnNotice($warnConfig['receivers'], $stock, $warnConfig);
                }
            }
        }

        return true;
    }

    // 发送预警通知
    private function sendWarnNotice($receivers, $stock, $warnConfig)
    {
        $receivers = json_decode($receivers, true);
        if (!is_array($receivers)) return false;

        $message = "【库存预警】{$stock['ticket_type']}({$stock['channel']})于{$stock['date']}的可用库存为{$stock['available_stock']}，已低于预警阈值{$warnConfig['warn_value']}，请及时处理。";

        // 实际项目中这里应该调用短信接口发送通知
        Log::record("发送库存预警通知给" . implode(',', $receivers) . "：{$message}");

        return true;
    }

    // 调整库存
    public function adjustStock($data)
    {
        Db::startTrans();
        try {
            $stock = $this->find($data['id']);
            if (!$stock) {
                throw new \Exception('库存记录不存在');
            }

            $beforeStock = $stock['available_stock'];
            $adjustValue = intval($data['adjust_value']);

            // 调整库存前记录类型
            Log::record("库存调整类型：{$data['adjust_type']}，调整数量：{$adjustValue}，库存ID：{$stock['id']}");

            // 根据调整类型更新库存
            if ($data['adjust_type'] == 1) {
                // 增加库存
                $stock['total_stock'] += $adjustValue;
                $stock['available_stock'] += $adjustValue;
            } else {
                // 减少库存
                if ($stock['available_stock'] < $adjustValue) {
                    throw new \Exception('可用库存不足，无法减少');
                }
                $stock['available_stock'] -= $adjustValue;
            }

            $stock->save();

            // 记录调整日志
            Db::name('stock_adjust_log')->insert([
                'stock_id' => $stock['id'],
                'ticket_type' => $stock['ticket_type'],
                'channel' => $stock['channel'],
                'date' => $stock['date'],
                'adjust_type' => $data['adjust_type'],
                'adjust_value' => $adjustValue,
                'before_stock' => $beforeStock,
                'after_stock' => $stock['available_stock'],
                'operator_id' => $data['operator_id'],
                'operator_name' => $data['operator_name'],
                'remark' => $data['remark'] ?? '',
                'create_time' => time()
            ]);

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            Log::error('库存调整失败：' . $e->getMessage());
            return false;
        }
    }
}