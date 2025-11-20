<?php
namespace app\admin\controller\fzly;
use think\Controller;

use app\admin\model\systemsetting\Basic;
use app\admin\model\systemsetting\StockWarn;
use app\admin\model\systemsetting\OrderRule;
use app\admin\model\systemsetting\PriceRule;
use app\admin\model\systemsetting\Role;
use app\admin\model\systemsetting\AuthGroup;
use app\admin\model\systemsetting\AuthRule;
use app\admin\model\systemsetting\AdminLog;
use app\common\controller\Backend;
use think\Db;
use think\Exception;
use think\Log;

class Systemsetting extends Controller
{
    protected $noNeedLogin = [];
    protected $noNeedRight = ['index', 'basic', 'stock_warn', 'order_rule', 'price_rule', 'role', 'log'];

    public function _initialize()
    {
        parent::_initialize();

        $this->model = new Basic();
//        $this->auth = new \think\Auth();
    }

    public function index()
    {
        // 初始化tab选项卡数据
        // Systemsetting.php 中 index() 方法的 $tabs 数组
        $tabs = [
            ['title' => '基础参数配置', 'url' => url('admin/fzly.Systemsetting/basic'), 'icon' => 'fa fa-cog'],
            ['title' => '库存预警配置', 'url' => url('admin/fzly.Systemsetting/stock_warn'), 'icon' => 'fa fa-bell'],
            ['title' => '订单规则配置', 'url' => url('admin/fzly.Systemsetting/order_rule'), 'icon' => 'fa fa-clock-o'],
            ['title' => '票价规则配置', 'url' => url('admin/fzly.Systemsetting/price_rule'), 'icon' => 'fa fa-money'],
            ['title' => '角色权限管理', 'url' => url('admin/fzly.Systemsetting/role'), 'icon' => 'fa fa-group'],
            ['title' => '操作日志管理', 'url' => url('admin/fzly.Systemsetting/log'), 'icon' => 'fa fa-file-text-o'],
        ];

        $this->assign('tabs', $tabs);
        $this->assign('current_tab', '基础参数配置');
        return $this->view->fetch('fzly/systemsetting/index');
    }

    // 基础参数配置
    public function basic()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            // 验证数据
            $validate = new \think\Validate([
                'scenic_name' => 'require|max:50',
                'contact_phone' => 'require|mobile',
                'address' => 'require|max:255',
                'refresh_interval' => 'require|number|between:1,60',
            ], [
                'scenic_name.require' => '景区名称不能为空',
                'contact_phone.require' => '联系电话不能为空',
                'contact_phone.mobile' => '联系电话格式不正确',
                'address.require' => '地址不能为空',
                'refresh_interval.require' => '数据刷新间隔不能为空',
                'refresh_interval.number' => '数据刷新间隔必须为数字',
                'refresh_interval.between' => '数据刷新间隔必须在1-60秒之间',
            ]);

            if (!$validate->check($data)) {
                $this->error($validate->getError());
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

            // 处理单选框
            $data['login_captcha'] = isset($data['login_captcha']) ? 1 : 0;
            $data['operation_log'] = isset($data['operation_log']) ? 1 : 0;

            // 新增或更新（仅保留一条配置）
            $exist = $this->model->find();
            if ($exist) {
                $result = $this->model->where('id', $exist['id'])->update($data);
            } else {
                $result = $this->model->save($data);
            }

            $result ? $this->success('配置保存成功，已实时生效', '') : $this->error('配置保存失败');
        }

        // 查询已有配置
        $basicInfo = $this->model->find() ?: [];

        // 语言选项
        $langOptions = [
            ['id' => 'zh-cn', 'name' => '简体中文'],
            ['id' => 'en-us', 'name' => '英文']
        ];

        $this->assign('langOptions', $langOptions);
        $this->assign('basicInfo', $basicInfo);
        $this->assign('current_tab', '基础参数配置');
        return $this->view->fetch('fzly/systemsetting/basic');
    }

    // 库存预警阈值配置
    public function stock_warn()
    {
        $stockWarnModel = new StockWarn();

        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['receivers'] = json_encode($data['receivers']);

            // 验证数据
            $validate = new \think\Validate([
                'channel' => 'require|in:官网,OTA,窗口',
                'date_start' => 'require|date',
                'date_end' => 'require|date|after:date_start',
                'ticket_type' => 'require|max:30',
                'warn_value' => 'require|number|gt:0|lt:10000',
            ], [
                'channel.require' => '渠道类型不能为空',
                'channel.in' => '渠道类型只能是官网、OTA或窗口',
                'date_start.require' => '生效开始日期不能为空',
                'date_start.date' => '生效开始日期格式不正确',
                'date_end.require' => '生效结束日期不能为空',
                'date_end.date' => '生效结束日期格式不正确',
                'date_end.after' => '生效结束日期必须在开始日期之后',
                'ticket_type.require' => '票种不能为空',
                'warn_value.require' => '预警阈值不能为空',
                'warn_value.number' => '预警阈值必须为数字',
                'warn_value.gt' => '预警阈值必须大于0',
                'warn_value.lt' => '预警阈值必须小于10000',
            ]);

            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }

            // 检查唯一性
            $where = [
                'channel' => $data['channel'],
                'date_start' => $data['date_start'],
                'date_end' => $data['date_end'],
                'ticket_type' => $data['ticket_type']
            ];
            if ($data['id']) {
                $where['id'] = ['neq', $data['id']];
            }
            $exist = $stockWarnModel->where($where)->find();
            if ($exist) {
                $this->error('同一渠道+日期范围+票种的配置已存在');
            }

            // 保存数据
            $result = $data['id'] ? $stockWarnModel->update($data) : $stockWarnModel->save($data);
            $result ? $this->success('配置保存成功', 'systemsetting/systemsetting/stock_warn') : $this->error('配置保存失败');
        }

        // 列表查询
        $list = $stockWarnModel->order('id desc')->select();
        foreach ($list as &$item) {
            $item['receivers'] = json_decode($item['receivers'], true);
        }

        $this->assign('list', $list);
        $this->assign('current_tab', '库存预警配置');
        return $this->view->fetch('fzly/systemsetting/stock_warn');
    }

    // 订单规则配置
    public function order_rule()
    {
        $orderRuleModel = new OrderRule();

        if ($this->request->isPost()) {
            $data = $this->request->post();

            // 验证数据
            $validate = new \think\Validate([
                'pay_timeout' => 'require|number|between:5,120',
                'stock_release_interval' => 'require|number|between:1,30',
            ], [
                'pay_timeout.require' => '支付超时时间不能为空',
                'pay_timeout.number' => '支付超时时间必须为数字',
                'pay_timeout.between' => '支付超时时间必须在5-120分钟之间',
                'stock_release_interval.require' => '库存释放间隔不能为空',
                'stock_release_interval.number' => '库存释放间隔必须为数字',
                'stock_release_interval.between' => '库存释放间隔必须在1-30分钟之间',
            ]);

            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }

            // 检查库存释放间隔是否小于支付超时时间
            if ($data['stock_release_interval'] >= $data['pay_timeout']) {
                $this->error('库存释放间隔必须小于支付超时时间');
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
        $this->assign('current_tab', '订单规则配置');
        return $this->view->fetch('fzly/systemsetting/order_rule');
    }

    // 票价规则配置
    public function price_rule()
    {
        $priceRuleModel = new PriceRule();

        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['apply_ticket_types'] = json_encode($data['apply_ticket_types']);
            $data['holiday_dates'] = json_encode($data['holiday_dates'] ?? []);

            // 验证数据
            $validate = new \think\Validate([
                'holiday_premium_rate' => 'require|number|between:100,200',
                'group_discount_base' => 'require|number|between:50,99',
            ], [
                'holiday_premium_rate.require' => '节假日溢价比例不能为空',
                'holiday_premium_rate.number' => '节假日溢价比例必须为数字',
                'holiday_premium_rate.between' => '节假日溢价比例必须在100%-200%之间',
                'group_discount_base.require' => '团体票折扣基准不能为空',
                'group_discount_base.number' => '团体票折扣基准必须为数字',
                'group_discount_base.between' => '团体票折扣基准必须在50%-99%之间',
            ]);

            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }

            // 检查节假日日期（启用溢价时）
            if ($data['holiday_premium_switch'] && empty($data['holiday_dates'])) {
                $this->error('启用节假日溢价时必须设置节假日日期');
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
        $this->assign('current_tab', '票价规则配置');
        return $this->view->fetch('fzly/systemsetting/price_rule');
    }

    // 角色权限管理
    public function role()
    {
        $roleModel = new Role();
        $authGroupModel = new AuthGroup();
        $authRuleModel = new AuthRule();

        if ($this->request->isPost()) {
            $action = $this->request->post('action');
            $data = $this->request->post();

            switch ($action) {
                case 'add_role':
                    // 新增角色
                    $validate = new \think\Validate([
                        'name' => 'require|max:30|unique:fa_auth_group',
                    ], [
                        'name.require' => '角色名称不能为空',
                        'name.max' => '角色名称不能超过30个字符',
                        'name.unique' => '角色名称已存在',
                    ]);

                    if (!$validate->check($data)) {
                        $this->error($validate->getError());
                    }

                    $result = $authGroupModel->save([
                        'name' => $data['name'],
                        'rules' => implode(',', $data['rules']),
                        'status' => 1,
                        'create_time' => time()
                    ]);
                    $result ? $this->success('角色添加成功', 'systemsetting/systemsetting/role') : $this->error('角色添加失败');
                    break;

                case 'edit_role':
                    // 编辑角色
                    $validate = new \think\Validate([
                        'id' => 'require|number',
                        'name' => 'require|max:30',
                    ], [
                        'id.require' => '角色ID不能为空',
                        'id.number' => '角色ID必须为数字',
                        'name.require' => '角色名称不能为空',
                        'name.max' => '角色名称不能超过30个字符',
                    ]);

                    if (!$validate->check($data)) {
                        $this->error($validate->getError());
                    }

                    $where = ['id' => $data['id']];
                    $exist = $authGroupModel->where($where)->find();
                    if (!$exist) {
                        $this->error('角色不存在');
                    }

                    $result = $authGroupModel->where($where)->update([
                        'name' => $data['name'],
                        'rules' => implode(',', $data['rules']),
                        'update_time' => time()
                    ]);
                    $result ? $this->success('角色更新成功', 'systemsetting/systemsetting/role') : $this->error('角色更新失败');
                    break;

                case 'delete_role':
                    // 删除角色
                    $validate = new \think\Validate([
                        'id' => 'require|number',
                    ], [
                        'id.require' => '角色ID不能为空',
                        'id.number' => '角色ID必须为数字',
                    ]);

                    if (!$validate->check($data)) {
                        $this->error($validate->getError());
                    }

                    $where = ['id' => $data['id']];
                    $exist = $authGroupModel->where($where)->find();
                    if (!$exist) {
                        $this->error('角色不存在');
                    }

                    // 检查是否有用户属于该角色
                    $userCount = Db::name('admin')->where(['group_id' => $data['id']])->count();
                    if ($userCount > 0) {
                        $this->error('该角色下还有用户，不能删除');
                    }

                    $result = $authGroupModel->where($where)->delete();
                    $result ? $this->success('角色删除成功', 'systemsetting/systemsetting/role') : $this->error('角色删除失败');
                    break;

                default:
                    $this->error('未知操作');
            }
        }

        // 获取所有角色
        $roles = $authGroupModel->order('id asc')->select();

        // 获取所有权限规则（系统设置相关）
        $authRules = $authRuleModel->where(['pid' => 1])->order('id asc')->select();

        $this->assign('roles', $roles);
        $this->assign('authRules', $authRules);
        $this->assign('current_tab', '角色权限管理');
        return $this->view->fetch('fzly/systemsetting/role');
    }

    // 操作日志管理
    public function log()
    {
        $logModel = new AdminLog();

        if ($this->request->isPost()) {
            $action = $this->request->post('action');
            $data = $this->request->post();

            switch ($action) {
                case 'clear_log':
                    // 清空日志
                    $result = $logModel->where('id > 0')->delete();
                    $result ? $this->success('操作日志已清空', 'systemsetting/systemsetting/log') : $this->error('日志清空失败');
                    break;

                default:
                    $this->error('未知操作');
            }
        }

        // 查询日志（支持分页）
        $page = $this->request->get('page', 1);
        $limit = $this->request->get('limit', 20);
        $offset = ($page - 1) * $limit;

        $where = [];
        $search = $this->request->get('search', '');
        if ($search) {
            $where['username|action|url'] = ['like', '%' . $search . '%'];
        }

        $total = $logModel->where($where)->count();
        $logs = $logModel->where($where)->order('id desc')->limit($offset, $limit)->select();

        $this->assign('logs', $logs);
        $this->assign('total', $total);
        $this->assign('page', $page);
        $this->assign('limit', $limit);
        $this->assign('current_tab', '操作日志管理');
        return $this->view->fetch('fzly/systemsetting/log');
    }

    // 删除库存预警配置
    public function stock_warn_delete($id)
    {
        $result = (new StockWarn())->destroy($id);
        $result ? $this->success('删除成功') : $this->error('删除失败');
    }
}