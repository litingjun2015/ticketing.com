<?php

namespace addons\fzly;

use app\admin\model\AuthRule;
use app\common\library\Menu;
use think\Addons;
use think\Hook;
use think\Loader;


/**
 * 插件
 */
class Fzly extends Addons
{


    /**
     * 应用初始化
     */
    public function appInit()
    {
        require_once __DIR__.'/helper.php';
        if (request()->isCli()) {
            \think\Console::addDefaultCommands([
                'addons\fzly\library\GatewayWorker\start'
            ]);
        }
        if (!class_exists("Grafika\Grafika")) {
            Loader::addNamespace('Grafika', ADDON_PATH . 'fzly' . DS . 'library' . DS . 'Grafika' . DS);
        }
        Hook::add('fzly_distribution', 'addons\fzly\behavior\DistributionHook');

    }

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu=[];
        $config_file= ADDON_PATH ."fzly" . DS.'config'.DS. "menu.php";
        if (is_file($config_file)) {
            $menu = include $config_file;
        }
        if($menu){
            Menu::create($menu);
        }
        return true;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {
        $info=get_addon_info('fzly');
        Menu::delete(isset($info['first_menu'])?$info['first_menu']:'fzly');
        return true;
    }

    /**
     * 插件启用方法
     * @return bool
     */
    public function enable()
    {
        $info = get_addon_info('fzly');
        Menu::enable(isset($info['first_menu']) ? $info['first_menu'] : 'fzly');
    }

    /**
     * 插件禁用方法
     * @return bool
     */
    public function disable()
    {
        $info=get_addon_info('fzly');
        Menu::disable(isset($info['first_menu'])?$info['first_menu']:'fzly');
    }

    /**
     * 插件更新方法
     */
    public function upgrade()
    {
        // 更新菜单
        $menu = self::getMenu();
        Menu::upgrade('fzly', $menu['new']);

        return true;
    }

    private static function getMenu()
    {
        $newMenu = [];
        $config_file = ADDON_PATH . "fzly" . DS . 'config' . DS . "menu.php";
        if (is_file($config_file)) {
            $newMenu = include $config_file;
        }
        $oldMenu = AuthRule::where('name', 'like', "fzly%")->select();
        $oldMenu = array_column($oldMenu, null, 'name');
        return ['new' => $newMenu, 'old' => $oldMenu];
    }
    

}
