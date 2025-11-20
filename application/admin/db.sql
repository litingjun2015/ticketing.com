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