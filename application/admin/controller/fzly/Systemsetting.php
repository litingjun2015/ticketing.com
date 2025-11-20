<?php

namespace app\admin\controller\fzly;

use app\admin\model\systemsetting\Basic;
use app\admin\model\systemsetting\StockWarn;
use app\admin\model\systemsetting\OrderRule;
use app\admin\model\systemsetting\PriceRule;
use app\common\controller\Backend;
use think\Db;
use think\Exception;
use think\Log;

class Systemsetting extends Backend
{
    // 初始化：设置权限节点
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new Basic();
    }

    // 1. 系统基础信息配置
    public function basic()
    {
        Log::info('这是一条调试日志 Systemsetting');

        if ($this->request->isPost()) {
            $data = $this->request->post();
            $basicModel = new Basic();

            // 验证数据
            $checkResult = $basicModel->checkData($data);
            if ($checkResult !== true) {
                $this->error($checkResult);
            }

            // 处理LOGO上传
            if ($this->request->file('logo')) {
                $file = $this->request->file('logo');
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . date('Ym'));
                if ($info) {
                    $data['logo'] = '/uploads/' . date('Ym') . '/' . $info->getSaveName();
                } else {
                    $this->error($file->getError());
                }
            }

            // 新增或更新（仅保留一条配置）
            $exist = $basicModel->find();
            if ($exist) {
                $result = $basicModel->where('id', $exist['id'])->update($data);
            } else {
                $result = $basicModel->save($data);
            }

            $result ? $this->success('配置保存成功，已实时生效', '') : $this->error('配置保存失败');
        }

        // 渲染页面：查询已有配置
        $basicInfo = (new Basic())->find() ?: [];
        $this->assign('basicInfo', $basicInfo);

        return $this->view->fetch('fzly/systemsetting/basic');
    }

    // 2. 库存预警阈值配置
    public function stockWarn()
    {
        $stockWarnModel = new StockWarn();
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['receivers'] = json_encode($data['receivers']);

            // 验证数据
            $validate = new \think\Validate($stockWarnModel->validateRule, $stockWarnModel->validateMsg);
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }

            // 检查唯一性
            if (!$stockWarnModel->checkUnique($data, $data['id'] ?? 0)) {
                $this->error('同一渠道+日期范围+票种的配置已存在');
            }

            // 保存数据
            $result = $data['id'] ? $stockWarnModel->update($data) : $stockWarnModel->save($data);
            $result ? $this->success('配置保存成功', 'systemsetting/systemsetting/stockWarn') : $this->error('配置保存失败');
        }

        // 列表查询
        $list = $stockWarnModel->order('id desc')->select();
        foreach ($list as &$item) {
            $item['receivers'] = json_decode($item['receivers'], true);
        }
        $this->assign('list', $list);
        return $this->view->fetch('fzly/systemsetting/stock_warn');
    }

    // 3. 订单规则配置
    public function orderRule()
    {
        $orderRuleModel = new OrderRule();
        if ($this->request->isPost()) {
            $data = $this->request->post();

            // 验证数据
            $validate = new \think\Validate($orderRuleModel->validateRule, $orderRuleModel->validateMsg);
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }

            // 新增或更新（仅保留一条配置）
            $exist = $orderRuleModel->find();
            if ($exist) {
                $result = $orderRuleModel->where('id', $exist['id'])->update($data);
            } else {
                $result = $orderRuleModel->save($data);
            }

            $result ? $this->success('配置保存成功，新订单将按规则生效', '') : $this->error('配置保存失败');
        }

        // 查询已有配置
        $orderRule = $orderRuleModel->find() ?: [];
        $this->assign('orderRule', $orderRule);
        return $this->view->fetch('fzly/systemsetting/order_rule');
    }

    // 4. 票价规则配置
    public function priceRule()
    {
        $priceRuleModel = new PriceRule();
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['apply_ticket_types'] = json_encode($data['apply_ticket_types']);
            $data['holiday_dates'] = json_encode($data['holiday_dates'] ?? []);

            // 验证数据
            $validate = new \think\Validate($priceRuleModel->validateRule, $priceRuleModel->validateMsg);
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }

            // 检查节假日日期（启用溢价时）
            $dateCheck = $priceRuleModel->checkHolidayDate($data);
            if ($dateCheck !== true) {
                $this->error($dateCheck);
            }

            // 新增或更新（仅保留一条配置）
            $exist = $priceRuleModel->find();
            if ($exist) {
                $result = $priceRuleModel->where('id', $exist['id'])->update($data);
            } else {
                $result = $priceRuleModel->save($data);
            }

            $result ? $this->success('配置保存成功，已实时生效', '') : $this->error('配置保存失败');
        }

        // 查询已有配置
        $priceRule = $priceRuleModel->find() ?: [];
        if (!empty($priceRule)) {
            $priceRule['apply_ticket_types'] = json_decode($priceRule['apply_ticket_types'], true);
            $priceRule['holiday_dates'] = json_decode($priceRule['holiday_dates'], true) ?? [];
        }
        $this->assign('priceRule', $priceRule);
        return $this->view->fetch('fzly/systemsetting/price_rule');
    }

    // 删除库存预警配置
    public function stockWarnDelete($id)
    {
        $result = (new StockWarn())->destroy($id);
        $result ? $this->success('删除成功') : $this->error('删除失败');
    }
}