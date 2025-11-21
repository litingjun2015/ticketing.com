<?php

namespace app\admin\controller\fzly\refund;

use app\admin\model\fzly\refund\Order as RefundOrderModel;
use app\admin\model\fzly\refund\Rule as RefundRuleModel;
use app\admin\model\User;
use app\common\controller\Backend;
use app\common\model\fzly\order\Order as OrderModel;
use think\Db;
use think\exception\DbException;
use think\exception\PDOException;
use think\Log;
use Yansongda\Pay\Pay;
use addons\epay\library\Service;

/**
 * 退单管理
 *
 * @icon fa fa-undo
 */
class Order extends Backend
{
    /**
     * RefundOrder模型对象
     * @var RefundOrderModel
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new RefundOrderModel();
        $this->view->assign("orderTypeList", $this->model->getOrderTypeList());
        $this->view->assign("refundTypeList", $this->model->getRefundTypeList());
        $this->view->assign("statusList", $this->model->getStatusList());
    }

    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            // with 中使用与模型一致的驼峰名 originalOrder
            $list = $this->model
                ->with(['user', 'originalOrder'])
                ->where($where)
                ->order($sort, $order)
                ->paginate($limit);

            foreach ($list as $row) {
                // 1. 确保退单号（自身表字段）正确赋值
                $row->refund_no = $row->refund_no ?? ''; // 字段名以数据库实际为准
                // 2. 确保申请退款金额赋值并处理默认值
                $row->refund_amount = $row->refund_amount ?? 0; // 默认为0，避免前端undefined
                // 3. 关联获取原订单号
                if ($originalOrder = $row->getRelation('originalOrder')) {
                    $row->original_order_no = $originalOrder->order_no ?? ''; // 原订单号别名，方便前端识别
                    $originalOrder->visible(['order_no', 'order_amount_total', 'pay_time']);
                } else {
                    $row->original_order_no = ''; // 无关联订单时设为空
                }

                // 对 user 关联也做空值判断（避免后续出现相同错误）
                if ($user = $row->getRelation('user')) {
                    $user->visible(['username']);
                }

                // 优化：使用模型关联直接获取，避免重复查询（可选）
                $row->admin_name = $row->admin_id ? User::where('id', $row->admin_id)->value('username') : '';
            }

            $result = array("total" => $list->total(), "rows" => $list->items());

            // 关键修改：按规范构造返回数据，添加 code:0
            return json([
                'code' => 0, // 成功状态码，必须为 0
                'msg'  => '获取退款订单列表成功', // 可选：提示信息
                'data' => $result // 原有业务数据放入 data 字段
            ]);
        }
        return $this->view->fetch();
    }

    /**
     * 创建退单
     */
    public function create($order_id = null)
    {
        if ($order_id === null) {
            $this->error(__('Parameter %s can not be empty', 'order_id'));
        }

        // 获取原订单信息
        $order = OrderModel::get($order_id);
        if (!$order) {
            $this->error(__('No Results were found'));
        }

        // 检查订单状态是否允许退款
        if (!in_array($order->status, [2, 3])) {
            $this->error(__('This order status does not allow refunds'));
        }

        // 检查是否已有退款记录
        $existingRefund = RefundOrderModel::where('order_id', $order_id)
            ->where('status', 'in', [1, 2, 4])
            ->find();
        if ($existingRefund) {
            $this->error(__('There is already a refund process for this order'));
        }

        if (false === $this->request->isPost()) {
            // 计算可退款金额和手续费
            $refundData = $this->calculateRefund($order);

            $this->view->assign('order', $order);
            $this->view->assign('refundData', $refundData);
            return $this->view->fetch();
        }

        $params = $this->request->post('row/a');
        if (empty($params)) {
            $this->error(__('Parameter %s can not be empty', ''));
        }

        // 重新计算退款信息确保准确性
        $refundData = $this->calculateRefund($order);

        Db::startTrans();
        try {
            $refundOrder = new RefundOrderModel();
            $refundOrder->refund_no = $refundOrder->generateRefundNo();
            $refundOrder->order_id = $order->id;
            $refundOrder->order_no = $order->order_no;
            $refundOrder->user_id = $order->user_id;
            $refundOrder->order_type = $order->order_type;
            $refundOrder->refund_amount = $order->order_amount_total;
            $refundOrder->fee_amount = $refundData['fee_amount'];
            $refundOrder->actual_amount = $refundData['actual_amount'];
            $refundOrder->refund_reason = $params['refund_reason'];
            $refundOrder->refund_type = 1; // 原路返回
            $refundOrder->status = 1; // 申请中
            $refundOrder->rule_id = $refundData['rule_id'];

            $result = $refundOrder->save();

            // 更新原订单状态为待退款
            $order->status = 4;
            $order->last_status = $order->status;
            $order->save();

            Db::commit();
            $this->success(__('Refund application submitted successfully'), url('index'));
        } catch (PDOException $e) {
            Db::rollback();
            $this->error(__('Operation failed: %s', $e->getMessage()));
        }
    }

    /**
     * 计算退款金额和手续费
     */
    protected function calculateRefund($order)
    {
        $orderAmount = $order->order_amount_total;
        $payTime = $order->pay_time;
        $orderType = $order->order_type;

        // 计算已支付时长（分钟）
        $timeDiff = (time() - $payTime) / 60;

        // 获取适用的退款规则
        $rule = RefundRuleModel::where('order_type', $orderType)
            ->where('status', 1)
            ->where('time_range', '<=', $timeDiff)
            ->order('time_range', 'desc')
            ->find();

        // 如果没有找到匹配的规则，使用默认规则（0手续费）
        if (!$rule) {
            return [
                'rule_id' => 0,
                'fee_rate' => 0,
                'fee_amount' => 0,
                'actual_amount' => $orderAmount
            ];
        }

        // 计算手续费
        $feeAmount = bcdiv(bcmul($orderAmount, $rule->fee_rate, 4), 100, 2);

        // 确保手续费在最小和最大之间
        if ($feeAmount < $rule->min_fee) {
            $feeAmount = $rule->min_fee;
        } elseif ($feeAmount > $rule->max_fee) {
            $feeAmount = $rule->max_fee;
        }

        // 计算实际退款金额
        $actualAmount = bcsub($orderAmount, $feeAmount, 2);
        if ($actualAmount < 0) {
            $actualAmount = 0;
        }

        return [
            'rule_id' => $rule->id,
            'fee_rate' => $rule->fee_rate,
            'fee_amount' => $feeAmount,
            'actual_amount' => $actualAmount,
            'time_diff' => round($timeDiff),
            'rule' => $rule
        ];
    }

    /**
     * 处理退单
     */
    public function handle($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }

        // 只能处理申请中的退单
        if ($row->status != 1) {
            $this->error(__('Only pending refunds can be processed'));
        }

        if (false === $this->request->isPost()) {
            $this->view->assign('row', $row);
            $this->view->assign('order', $row->order);
            return $this->view->fetch();
        }

        $params = $this->request->post('row/a');
        if (empty($params) || !isset($params['status'])) {
            $this->error(__('Parameter %s can not be empty', ''));
        }

        // 获取原订单
        $order = OrderModel::get($row->order_id);
        if (!$order) {
            $this->error(__('Original order not found'));
        }

        Db::startTrans();
        try {
            $row->admin_id = $this->auth->id;
            $row->handle_time = time();
            $row->handle_note = $params['handle_note'] ?? '';

            if ($params['status'] == 2) {
                // 同意退款，执行退款操作
                $row->status = 2;
                $row->save();

                // 调用支付接口进行退款
                $refundResult = $this->doRefund($order, $row);

                if ($refundResult['success']) {
                    $row->status = 4;
                    $row->refund_transaction_id = $refundResult['refund_id'];
                    $row->save();

                    // 更新订单状态为已取消
                    $order->status = 5;
                    $order->save();

                    // 同步至财务模块（此处仅做示例，实际应根据财务模块接口处理）
                    $this->syncToFinance($row);
                } else {
                    $row->status = 5;
                    $row->save();
                    throw new \Exception($refundResult['message']);
                }
            } else {
                // 拒绝退款
                $row->status = 3;
                $row->save();

                // 恢复原订单状态
                $order->status = $order->last_status;
                $order->save();
            }

            Db::commit();
            $this->success(__('Operation successful'), url('index'));
        } catch (\Exception $e) {
            Db::rollback();
            Log::error('Refund handle error: ' . $e->getMessage() . ' File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            $this->error(__('Operation failed: %s', $e->getMessage()));
        }
    }

    /**
     * 执行退款操作
     */
    protected function doRefund($order, $refundOrder)
    {
        try {
            $config = Service::getConfig();
            $pay = Pay::wechat($config);

            $refundData = [
                'out_trade_no' => $order->out_trade_no,
                'out_refund_no' => $refundOrder->refund_no,
                'total_fee' => bcmul($order->order_amount_total, 100, 0), // 原订单金额(分)
                'refund_fee' => bcmul($refundOrder->actual_amount, 100, 0), // 退款金额(分)
                'notify_url' => request()->domain() . '/addons/fzly/refund/notify',
            ];

            $result = $pay->refund($refundData);
            Log::info('Refund result: ' . json_encode($result));

            if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
                return [
                    'success' => true,
                    'refund_id' => $result['refund_id']
                ];
            } else {
                return [
                    'success' => false,
                    'message' => $result['err_code_des'] ?? 'Refund failed'
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * 同步至财务模块
     */
    protected function syncToFinance($refundOrder)
    {
        // 此处实现同步至财务模块的逻辑
        Log::info('Sync refund to finance: ' . $refundOrder->refund_no);
        // 实际项目中应调用财务模块的接口或直接操作财务相关数据表
    }

    /**
     * 批量退单
     */
    public function batchRefund()
    {
        $ids = $this->request->post('ids');
        if (empty($ids)) {
            $this->error(__('Please select at least one order'));
        }

        $ids = explode(',', $ids);
        $successCount = 0;
        $failCount = 0;
        $failMessages = [];

        foreach ($ids as $orderId) {
            try {
                // 检查订单是否存在且状态允许退款
                $order = OrderModel::get($orderId);
                if (!$order) {
                    throw new \Exception(__('Order %s not found', $orderId));
                }

                if (!in_array($order->status, [2, 3])) {
                    throw new \Exception(__('Order %s status does not allow refunds', $order->order_no));
                }

                // 检查是否已有退款记录
                $existingRefund = RefundOrderModel::where('order_id', $orderId)
                    ->where('status', 'in', [1, 2, 4])
                    ->find();
                if ($existingRefund) {
                    throw new \Exception(__('Order %s already has a refund process', $order->order_no));
                }

                // 计算退款信息
                $refundData = $this->calculateRefund($order);

                // 创建退单记录
                Db::startTrans();

                $refundOrder = new RefundOrderModel();
                $refundOrder->refund_no = $refundOrder->generateRefundNo();
                $refundOrder->order_id = $order->id;
                $refundOrder->order_no = $order->order_no;
                $refundOrder->user_id = $order->user_id;
                $refundOrder->order_type = $order->order_type;
                $refundOrder->refund_amount = $order->order_amount_total;
                $refundOrder->fee_amount = $refundData['fee_amount'];
                $refundOrder->actual_amount = $refundData['actual_amount'];
                $refundOrder->refund_reason = __('Batch refund');
                $refundOrder->refund_type = 1;
                $refundOrder->status = 1;
                $refundOrder->rule_id = $refundData['rule_id'];
                $refundOrder->save();

                // 更新订单状态
                $order->status = 4;
                $order->last_status = $order->status;
                $order->save();

                Db::commit();
                $successCount++;
            } catch (\Exception $e) {
                Db::rollback();
                $failCount++;
                $failMessages[] = $e->getMessage();
            }
        }

        $result = [
            'success' => $successCount,
            'fail' => $failCount,
            'messages' => $failMessages
        ];

        return json($result);
    }

    /**
     * 退单详情
     */
    public function detail($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }

        $this->view->assign('row', $row);
        $this->view->assign('order', $row->order);
        $this->view->assign('user', $row->user);
        $this->view->assign('rule', $row->rule);
        $this->view->assign('admin', $row->admin_id ? User::get($row->admin_id) : null);

        return $this->view->fetch();
    }
}