-- 1. 系统基础信息表
CREATE TABLE `fa_system_setting_basic` (
                                           `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
                                           `scenic_name` varchar(50) NOT NULL COMMENT '景区名称',
                                           `logo` varchar(255) DEFAULT '' COMMENT 'LOGO路径',
                                           `contact_phone` char(11) NOT NULL COMMENT '联系电话',
                                           `address` varchar(255) NOT NULL COMMENT '详细地址',
                                           `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
                                           `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
                                           PRIMARY KEY (`id`),
                                           UNIQUE KEY `uniq_scenic_name` (`scenic_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统基础信息配置';

-- 2. 库存预警阈值配置表
CREATE TABLE `fa_system_setting_stock_warn` (
                                                `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
                                                `channel` varchar(20) NOT NULL COMMENT '渠道类型（官网/OTA/窗口）',
                                                `date_start` date NOT NULL COMMENT '生效开始日期',
                                                `date_end` date NOT NULL COMMENT '生效结束日期',
                                                `ticket_type` varchar(30) NOT NULL COMMENT '票种',
                                                `warn_value` int(5) NOT NULL COMMENT '预警阈值',
                                                `receivers` text NOT NULL COMMENT '通知接收人（手机号数组JSON）',
                                                `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
                                                `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
                                                PRIMARY KEY (`id`),
                                                UNIQUE KEY `uniq_channel_date_ticket` (`channel`,`date_start`,`date_end`,`ticket_type`),
                                                CHECK (`warn_value` > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='库存预警阈值配置';

-- 3. 订单规则配置表
CREATE TABLE `fa_system_setting_order_rule` (
                                                `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
                                                `pay_timeout` int(3) NOT NULL DEFAULT '30' COMMENT '支付超时时间（分钟）',
                                                `stock_release_interval` int(2) NOT NULL DEFAULT '5' COMMENT '库存释放间隔（分钟）',
                                                `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态（1启用/0禁用）',
                                                `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
                                                `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
                                                PRIMARY KEY (`id`),
                                                CHECK (`pay_timeout` BETWEEN 5 AND 120),
                                                CHECK (`stock_release_interval` BETWEEN 1 AND 30),
                                                CHECK (`stock_release_interval` < `pay_timeout`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='订单规则配置';

-- 4. 票价规则配置表
CREATE TABLE `fa_system_setting_price_rule` (
                                                `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
                                                `holiday_premium_switch` tinyint(1) NOT NULL DEFAULT '0' COMMENT '节假日溢价开关（1启用/0禁用）',
                                                `holiday_premium_rate` int(3) NOT NULL DEFAULT '100' COMMENT '溢价比例（100%-200%）',
                                                `group_discount_base` int(2) NOT NULL DEFAULT '90' COMMENT '团体票折扣基准（50%-99%）',
                                                `apply_ticket_types` text NOT NULL COMMENT '适用票种（数组JSON）',
                                                `holiday_dates` text COMMENT '绑定节假日日期（数组JSON）',
                                                `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态（1启用/0禁用）',
                                                `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
                                                `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
                                                PRIMARY KEY (`id`),
                                                CHECK (`holiday_premium_rate` BETWEEN 100 AND 200),
                                                CHECK (`group_discount_base` BETWEEN 50 AND 99)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='票价规则配置';


-- 系统基础信息测试数据
INSERT INTO `fa_system_setting_basic` (`scenic_name`, `logo`, `contact_phone`, `address`, `create_time`, `update_time`)
VALUES ('示例景区', '/uploads/202405/scenic-logo.png', '13800138000', '北京市朝阳区XX路XX号', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- 库存预警阈值测试数据
INSERT INTO `fa_system_setting_stock_warn` (`channel`, `date_start`, `date_end`, `ticket_type`, `warn_value`, `receivers`, `create_time`, `update_time`)
VALUES ('OTA', '2024-06-01', '2024-06-30', '成人票', 50, '["13800138000","13900139000"]', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- 订单规则测试数据
INSERT INTO `fa_system_setting_order_rule` (`pay_timeout`, `stock_release_interval`, `status`, `create_time`, `update_time`)
VALUES (30, 5, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- 票价规则测试数据
INSERT INTO `fa_system_setting_price_rule` (`holiday_premium_switch`, `holiday_premium_rate`, `group_discount_base`, `apply_ticket_types`, `holiday_dates`, `status`, `create_time`, `update_time`)
VALUES (1, 120, 90, '["成人票","亲子票"]', '["2024-06-10","2024-06-12"]', 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- 2025/11/20
-- 扩展系统基础参数配置表
ALTER TABLE `fa_system_setting_basic`
    ADD COLUMN `system_name` varchar(100) NOT NULL DEFAULT '景区票务系统' COMMENT '系统名称',
ADD COLUMN `copyright` varchar(255) NOT NULL DEFAULT '© 2024 景区票务系统 版权所有' COMMENT '版权信息',
ADD COLUMN `icp` varchar(50) DEFAULT '' COMMENT 'ICP备案号',
ADD COLUMN `refresh_interval` int(3) NOT NULL DEFAULT 5 COMMENT '数据刷新间隔(秒)',
ADD COLUMN `login_captcha` tinyint(1) NOT NULL DEFAULT 1 COMMENT '登录验证码(1启用/0禁用)',
ADD COLUMN `operation_log` tinyint(1) NOT NULL DEFAULT 1 COMMENT '操作日志记录(1启用/0禁用)',
ADD COLUMN `default_lang` varchar(10) NOT NULL DEFAULT 'zh-cn' COMMENT '默认语言',
ADD COLUMN `time_format` varchar(50) NOT NULL DEFAULT 'Y-m-d H:i:s' COMMENT '时间显示格式';

-- 更新测试数据
UPDATE `fa_system_setting_basic` SET
                                     `system_name` = '黄山景区票务系统',
                                     `copyright` = '© 2024 黄山景区管理局 版权所有',
                                     `icp` = '皖ICP备12345678号',
                                     `refresh_interval` = 10,
                                     `login_captcha` = 1,
                                     `operation_log` = 1,
                                     `default_lang` = 'zh-cn',
                                     `time_format` = 'Y-m-d H:i:s'
WHERE `id` = 1;

-- 2025/11/20，2
-- 系统基础信息表
CREATE TABLE `fa_system_setting_basic` (
                                           `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
                                           `scenic_name` VARCHAR(50) NOT NULL COMMENT '景区名称',
                                           `logo` VARCHAR(255) DEFAULT '' COMMENT 'LOGO路径',
                                           `contact_phone` CHAR(11) NOT NULL COMMENT '联系电话',
                                           `address` VARCHAR(255) NOT NULL COMMENT '详细地址',
                                           `system_name` VARCHAR(100) NOT NULL DEFAULT '景区票务系统' COMMENT '系统名称',
                                           `copyright` VARCHAR(255) NOT NULL DEFAULT '© 2024 景区票务系统 版权所有' COMMENT '版权信息',
                                           `icp` VARCHAR(50) DEFAULT '' COMMENT 'ICP备案号',
                                           `refresh_interval` INT(3) NOT NULL DEFAULT 5 COMMENT '数据刷新间隔(秒)',
                                           `login_captcha` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '登录验证码(1启用/0禁用)',
                                           `operation_log` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '操作日志记录(1启用/0禁用)',
                                           `default_lang` VARCHAR(10) NOT NULL DEFAULT 'zh-cn' COMMENT '默认语言',
                                           `time_format` VARCHAR(50) NOT NULL DEFAULT 'Y-m-d H:i:s' COMMENT '时间显示格式',
                                           `create_time` INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
                                           `update_time` INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
                                           PRIMARY KEY (`id`),
                                           UNIQUE KEY `uniq_scenic_name` (`scenic_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统基础信息配置';

-- 插入测试数据
INSERT INTO `fa_system_setting_basic`
(`scenic_name`, `logo`, `contact_phone`, `address`, `create_time`, `update_time`)
VALUES
    ('黄山景区', '/uploads/202405/scenic-logo.png', '13800138000', '安徽省黄山市黄山区', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- 库存预警阈值配置表
CREATE TABLE `fa_system_setting_stock_warn` (
                                                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
                                                `channel` VARCHAR(20) NOT NULL COMMENT '渠道类型（官网/OTA/窗口）',
                                                `date_start` DATE NOT NULL COMMENT '生效开始日期',
                                                `date_end` DATE NOT NULL COMMENT '生效结束日期',
                                                `ticket_type` VARCHAR(30) NOT NULL COMMENT '票种',
                                                `warn_value` INT(5) NOT NULL COMMENT '预警阈值',
                                                `receivers` TEXT NOT NULL COMMENT '通知接收人（手机号数组JSON）',
                                                `create_time` INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
                                                `update_time` INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
                                                PRIMARY KEY (`id`),
                                                UNIQUE KEY `uniq_channel_date_ticket` (`channel`,`date_start`,`date_end`,`ticket_type`),
                                                CHECK (`warn_value` > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='库存预警阈值配置';

-- 插入测试数据
INSERT INTO `fa_system_setting_stock_warn`
(`channel`, `date_start`, `date_end`, `ticket_type`, `warn_value`, `receivers`, `create_time`, `update_time`)
VALUES
    ('OTA', '2024-06-01', '2024-06-30', '成人票', 50, '["13800138000","13900139000"]', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
    ('官网', '2024-06-01', '2024-06-30', '儿童票', 30, '["13800138000"]', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());


-- 订单规则配置表
CREATE TABLE `fa_system_setting_order_rule` (
                                                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
                                                `pay_timeout` INT(3) NOT NULL DEFAULT '30' COMMENT '支付超时时间（分钟）',
                                                `stock_release_interval` INT(2) NOT NULL DEFAULT '5' COMMENT '库存释放间隔（分钟）',
                                                `status` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '状态（1启用/0禁用）',
                                                `create_time` INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
                                                `update_time` INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
                                                PRIMARY KEY (`id`),
                                                CHECK (`pay_timeout` BETWEEN 5 AND 120),
                                                CHECK (`stock_release_interval` BETWEEN 1 AND 30),
                                                CHECK (`stock_release_interval` < `pay_timeout`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='订单规则配置';

-- 插入测试数据
INSERT INTO `fa_system_setting_order_rule`
(`pay_timeout`, `stock_release_interval`, `status`, `create_time`, `update_time`)
VALUES
    (30, 5, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- 票价规则配置表
CREATE TABLE `fa_system_setting_price_rule` (
                                                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
                                                `holiday_premium_switch` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '节假日溢价开关（1启用/0禁用）',
                                                `holiday_premium_rate` INT(3) NOT NULL DEFAULT '100' COMMENT '溢价比例（100%-200%）',
                                                `group_discount_base` INT(2) NOT NULL DEFAULT '90' COMMENT '团体票折扣基准（50%-99%）',
                                                `apply_ticket_types` TEXT NOT NULL COMMENT '适用票种（数组JSON）',
                                                `holiday_dates` TEXT COMMENT '绑定节假日日期（数组JSON）',
                                                `status` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '状态（1启用/0禁用）',
                                                `create_time` INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
                                                `update_time` INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
                                                PRIMARY KEY (`id`),
                                                CHECK (`holiday_premium_rate` BETWEEN 100 AND 200),
                                                CHECK (`group_discount_base` BETWEEN 50 AND 99)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='票价规则配置';

-- 插入测试数据
INSERT INTO `fa_system_setting_price_rule`
(`holiday_premium_switch`, `holiday_premium_rate`, `group_discount_base`, `apply_ticket_types`, `holiday_dates`, `status`, `create_time`, `update_time`)
VALUES
    (1, 120, 90, '["成人票","亲子票"]', '["2024-06-10","2024-06-12"]', 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP());


-- 角色表
CREATE TABLE `fa_auth_group` (
                                 `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
                                 `pid` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '父级ID',
                                 `name` VARCHAR(30) NOT NULL COMMENT '角色名称',
                                 `rules` TEXT COMMENT '权限规则',
                                 `status` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '状态',
                                 `create_time` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
                                 `update_time` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
                                 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色组表';

-- 权限规则表
CREATE TABLE `fa_auth_rule` (
                                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
                                `pid` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '父级ID',
                                `type` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '类型（1菜单/2规则）',
                                `name` VARCHAR(100) NOT NULL COMMENT '规则名称',
                                `title` VARCHAR(50) NOT NULL COMMENT '规则标题',
                                `icon` VARCHAR(50) DEFAULT '' COMMENT '图标',
                                `condition` VARCHAR(255) DEFAULT '' COMMENT '条件',
                                `status` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '状态',
                                `create_time` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
                                `update_time` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
                                PRIMARY KEY (`id`),
                                UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='权限规则表';

-- 管理员表
CREATE TABLE `fa_admin` (
                            `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
                            `username` VARCHAR(30) NOT NULL COMMENT '用户名',
                            `password` VARCHAR(32) NOT NULL COMMENT '密码',
                            `group_id` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '角色组ID',
                            `status` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '状态',
                            `loginfailure` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '登录失败次数',
                            `logintime` INT(10) UNSIGNED DEFAULT '0' COMMENT '最后登录时间',
                            `loginip` VARCHAR(50) DEFAULT '' COMMENT '最后登录IP',
                            `create_time` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
                            `update_time` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
                            PRIMARY KEY (`id`),
                            UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='管理员表';

-- 操作日志表
CREATE TABLE `fa_admin_log` (
                                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
                                `admin_id` INT(10) UNSIGNED NOT NULL COMMENT '管理员ID',
                                `username` VARCHAR(30) NOT NULL COMMENT '管理员用户名',
                                `action` VARCHAR(50) NOT NULL COMMENT '操作行为',
                                `method` VARCHAR(10) NOT NULL COMMENT '请求方法',
                                `url` VARCHAR(255) NOT NULL COMMENT '请求URL',
                                `ip` VARCHAR(50) NOT NULL COMMENT '操作IP',
                                `useragent` VARCHAR(255) DEFAULT '' COMMENT 'User-Agent',
                                `create_time` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '操作时间',
                                `params` TEXT COMMENT '请求参数',
                                PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='管理员操作日志表';

-- 插入预设角色
INSERT INTO `fa_auth_group`
(`name`, `rules`, `status`, `create_time`, `update_time`)
VALUES
    ('超管', '*', 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
    ('售票员', 'systemsetting/stock_warn,systemsetting/order_rule,systemsetting/price_rule', 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
    ('财务', 'systemsetting/basic,systemsetting/role,systemsetting/log', 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
    ('验票员', 'systemsetting/stock_warn', 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
    ('OTA运营', 'systemsetting/stock_warn,systemsetting/price_rule', 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- 插入权限规则（系统设置模块）
INSERT INTO `fa_auth_rule`
(`pid`, `type`, `name`, `title`, `icon`, `status`, `create_time`, `update_time`)
VALUES
    (0, 1, 'systemsetting', '系统设置', 'fa fa-cog', 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
    (1, 1, 'systemsetting/basic', '基础参数配置', 'fa fa-cog', 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
    (1, 1, 'systemsetting/stock_warn', '库存预警配置', 'fa fa-bell', 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
    (1, 1, 'systemsetting/order_rule', '订单规则配置', 'fa fa-clock-o', 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
    (1, 1, 'systemsetting/price_rule', '票价规则配置', 'fa fa-money', 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
    (1, 1, 'systemsetting/role', '角色权限管理', 'fa fa-group', 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
    (1, 1, 'systemsetting/log', '操作日志管理', 'fa fa-file-text-o', 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- 插入测试管理员用户
INSERT INTO `fa_admin`
(`username`, `password`, `group_id`, `status`, `create_time`, `update_time`)
VALUES
    ('admin', '21232f297a57a5a743894a0e4a801fc3', 1, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()), -- 密码: admin
    ('ticket_seller', '21232f297a57a5a743894a0e4a801fc3', 2, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()), -- 密码: admin
    ('finance', '21232f297a57a5a743894a0e4a801fc3', 3, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()); -- 密码: admin

--- 有问题
INSERT INTO `fa_auth_group` ​
(`id`, `pid`, `name`, `rules`, `status`, `createtime`, `updatetime`) ​
VALUES ​
    (6, 0, '超管', '*', '1', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),​
    (7, 1, '售票员', 'systemsetting/stock_warn,systemsetting/order_rule,systemsetting/price_rule',  '1', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),​
    (8, 1, '财务', 'systemsetting/basic,systemsetting/role,systemsetting/log',  '1', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),​
    (9, 1, '验票员', 'systemsetting/stock_warn',  '1', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),​
    (10, 1, 'OTA运营', 'systemsetting/stock_warn,systemsetting/price_rule',  '1', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());


-- 插入权限规则（系统设置模块）​
INSERT INTO `fa_auth_rule` ​
(`pid`, `type`, `name`, `title`, `icon`, `status`, `create_time`, `update_time`) ​
VALUES ​
    (0, 1, 'systemsetting', '系统设置', 'fa fa-cog', 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),​
    (1, 1, 'systemsetting/basic', '基础参数配置', 'fa fa-cog', 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),​
    (1, 1, 'systemsetting/stock_warn', '库存预警配置', 'fa fa-bell', 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),​
    (1, 1, 'systemsetting/order_rule', '订单规则配置', 'fa fa-clock-o', 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),​
    (1, 1, 'systemsetting/price_rule', '票价规则配置', 'fa fa-money', 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),​
    (1, 1, 'systemsetting/role', '角色权限管理', 'fa fa-group', 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),​
    (1, 1, 'systemsetting/log', '操作日志管理', 'fa fa-file-text-o', 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP());


-- 插入测试管理员用户​
INSERT INTO `fa_admin` ​
(`username`, `password`, `group_id`, `status`, `create_time`, `update_time`) ​
VALUES ​
    ('admin', '21232f297a57a5a743894a0e4a801fc3', 1, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()), -- 密码: admin​
    ('ticket_seller', '21232f297a57a5a743894a0e4a801fc3', 2, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()), -- 密码: admin​
    ('finance', '21232f297a57a5a743894a0e4a801fc3', 3, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()); -- 密码: admin


-- 系统设置模块权限节点
INSERT INTO `fa_auth_rule` (`pid`, `type`, `name`, `title`, `icon`, `status`, `create_time`, `update_time`) VALUES
                                                                                                                (0, 1, 'systemsetting', '系统设置', 'fa fa-cog', 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                                                                                                                (1, 1, 'systemsetting/basic', '基础参数配置', 'fa fa-cog', 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                                                                                                                (1, 1, 'systemsetting/stock_warn', '库存预警配置', 'fa fa-bell', 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                                                                                                                (1, 1, 'systemsetting/order_rule', '订单规则配置', 'fa fa-clock-o', 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                                                                                                                (1, 1, 'systemsetting/price_rule', '票价规则配置', 'fa fa-money', 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                                                                                                                (1, 1, 'systemsetting/role', '角色权限管理', 'fa fa-group', 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                                                                                                                (1, 1, 'systemsetting/log', '操作日志管理', 'fa fa-file-text-o', 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP());
-- 2025/11/21
-- 为门票表添加价格、使用规则、适用人群字段
ALTER TABLE `fa_fzly_admission`
    ADD COLUMN `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT '门票价格' AFTER `hot`,
ADD COLUMN `use_rules` TEXT COMMENT '使用规则' AFTER `price`,
ADD COLUMN `applicable_people` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '适用人群（逗号分隔）' AFTER `use_rules`;--

--
-- 库存信息表
CREATE TABLE `fa_stock` (
                            `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
                            `ticket_type` varchar(30) NOT NULL COMMENT '票种',
                            `channel` varchar(20) NOT NULL COMMENT '渠道类型（官网/OTA/窗口）',
                            `total_stock` int(10) NOT NULL DEFAULT 0 COMMENT '总库存',
                            `used_stock` int(10) NOT NULL DEFAULT 0 COMMENT '已使用库存',
                            `available_stock` int(10) NOT NULL DEFAULT 0 COMMENT '可用库存',
                            `lock_stock` int(10) NOT NULL DEFAULT 0 COMMENT '锁定库存',
                            `date` date NOT NULL COMMENT '库存日期',
                            `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态（1启用/0禁用）',
                            `create_time` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '创建时间',
                            `update_time` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '更新时间',
                            PRIMARY KEY (`id`),
                            UNIQUE KEY `uniq_ticket_channel_date` (`ticket_type`,`channel`,`date`),
                            KEY `idx_available_stock` (`available_stock`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='库存信息表';

-- 库存调整记录表
CREATE TABLE `fa_stock_adjust_log` (
                                       `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
                                       `stock_id` int(10) unsigned NOT NULL COMMENT '库存ID',
                                       `ticket_type` varchar(30) NOT NULL COMMENT '票种',
                                       `channel` varchar(20) NOT NULL COMMENT '渠道类型',
                                       `date` date NOT NULL COMMENT '库存日期',
                                       `adjust_type` tinyint(1) NOT NULL COMMENT '调整类型（1增加/2减少）',
                                       `adjust_value` int(10) NOT NULL COMMENT '调整数量',
                                       `before_stock` int(10) NOT NULL COMMENT '调整前库存',
                                       `after_stock` int(10) NOT NULL COMMENT '调整后库存',
                                       `operator_id` int(10) unsigned NOT NULL COMMENT '操作人ID',
                                       `operator_name` varchar(50) NOT NULL COMMENT '操作人姓名',
                                       `remark` varchar(255) DEFAULT '' COMMENT '调整备注',
                                       `create_time` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '创建时间',
                                       PRIMARY KEY (`id`),
                                       KEY `idx_stock_id` (`stock_id`),
                                       KEY `idx_create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='库存调整记录表';

-- 库存预警记录表
CREATE TABLE `fa_stock_warn_log` (
                                     `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
                                     `stock_id` int(10) unsigned NOT NULL COMMENT '库存ID',
                                     `ticket_type` varchar(30) NOT NULL COMMENT '票种',
                                     `channel` varchar(20) NOT NULL COMMENT '渠道类型',
                                     `date` date NOT NULL COMMENT '库存日期',
                                     `current_stock` int(10) NOT NULL COMMENT '当前库存',
                                     `warn_value` int(10) NOT NULL COMMENT '预警阈值',
                                     `warn_time` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '预警时间',
                                     `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '处理状态（0未处理/1已处理）',
                                     `process_time` int(10) unsigned DEFAULT 0 COMMENT '处理时间',
                                     `process_user_id` int(10) unsigned DEFAULT 0 COMMENT '处理人ID',
                                     `process_user_name` varchar(50) DEFAULT '' COMMENT '处理人姓名',
                                     PRIMARY KEY (`id`),
                                     KEY `idx_stock_id` (`stock_id`),
                                     KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='库存预警记录表';

-- 插入测试数据
INSERT INTO `fa_stock`
(`ticket_type`, `channel`, `total_stock`, `used_stock`, `available_stock`, `lock_stock`, `date`, `create_time`, `update_time`)
VALUES
    ('成人票', '官网', 1000, 350, 600, 50, '2024-07-01', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
    ('成人票', 'OTA', 800, 200, 550, 50, '2024-07-01', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
    ('儿童票', '官网', 500, 100, 380, 20, '2024-07-01', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
    ('成人票', '官网', 1000, 200, 750, 50, '2024-07-02', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- 插入库存预警配置测试数据（扩展已有的表）
INSERT INTO `fa_system_setting_stock_warn`
(`channel`, `date_start`, `date_end`, `ticket_type`, `warn_value`, `receivers`, `create_time`, `update_time`)
VALUES
    ('官网', '2024-07-01', '2024-07-31', '成人票', 100, '["13800138000","13900139000"]', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
    ('OTA', '2024-07-01', '2024-07-31', '成人票', 80, '["13800138000"]', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
    ('官网', '2024-07-01', '2024-07-31', '儿童票', 50, '["13800138000","13900139000"]', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());