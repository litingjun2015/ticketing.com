<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

// 定义默认路由规则（如果需要）
// Route::rule('/', 'index/index');

// 解决你的控制器路径问题：将访问 fzly/systemsetting/index 映射到正确的控制器
// 格式：Route::请求方法('访问路径', '模块/控制器子目录.控制器名/操作方法')
Route::rule('fzly/systemsetting/:action', 'admin/fzly.Systemsetting/:action');

// 如果有其他操作方法（如 edit、save 等），可以批量配置
// Route::rule('fzly/systemsetting/:action', 'admin/fzly.Systemsetting/:action', 'GET|POST');

// 其他自定义路由规则可以添加在这里
// 例如：
// Route::get('user/:id', 'index/user/read');
// Route::post('user', 'index/user/save');

return [];