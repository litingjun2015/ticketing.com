<?php
namespace app\admin\model\systemsetting;

use think\Model;

class Role extends Model
{
    // 对应的数据表名（不含前缀，假设表前缀已在配置中设置）
    protected $name = 'auth_group';
    // 主键字段名
    protected $pk = 'id';
    // 自动写入时间戳（create_time 和 update_time）
    protected $autoWriteTimestamp = true;

    // 数据验证规则
    protected $validateRule = [
        'name' => 'require|max:30|unique:auth_group',
        'rules' => 'array',
        'status' => 'in:0,1',
    ];

    // 验证提示信息
    protected $validateMsg = [
        'name.require' => '角色名称不能为空',
        'name.max' => '角色名称不能超过30个字符',
        'name.unique' => '角色名称已存在',
        'status.in' => '状态值必须为0或1',
    ];

    /**
     * 验证角色数据（兼容父类方法签名）
     * @param array $data 待验证的数据
     * @param mixed $rule 验证规则
     * @param bool $batch 批量验证
     * @return true|string 验证通过返回true，否则返回错误信息
     */
    public function validateData($data, $rule = null, $batch = null)
    {
        // 优先使用传入的规则，否则使用类内定义的规则
        $validateRule = $rule ?? $this->validateRule;
        $validate = new \think\Validate($validateRule, $this->validateMsg);
        if (!$validate->check($data)) {
            return $validate->getError();
        }
        return true;
    }

    /**
     * 获取角色列表（带状态文本）
     * @return array
     */
    public function getList()
    {
        $list = $this->order('id asc')->select()->toArray();
        foreach ($list as &$item) {
            $item['status_text'] = $item['status'] == 1 ? '正常' : '禁用';
        }
        return $list;
    }

    /**
     * 检查角色是否存在
     * @param int $id 角色ID
     * @return bool
     */
    public function exists($id)
    {
        return $this->where('id', $id)->count() > 0;
    }
}