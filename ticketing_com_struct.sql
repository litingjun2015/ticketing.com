/*
 Navicat Premium Data Transfer

 Source Server         : 127.0.0.1
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : ticketing_com

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 27/11/2025 14:31:55
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for fa_admin
-- ----------------------------
DROP TABLE IF EXISTS `fa_admin`;
CREATE TABLE `fa_admin`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '昵称',
  `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '密码',
  `salt` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '密码盐',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '头像',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '电子邮箱',
  `mobile` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '手机号码',
  `loginfailure` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '失败次数',
  `logintime` bigint(16) NULL DEFAULT NULL COMMENT '登录时间',
  `loginip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '登录IP',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  `token` varchar(59) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT 'Session标识',
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'normal' COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_admin_log
-- ----------------------------
DROP TABLE IF EXISTS `fa_admin_log`;
CREATE TABLE `fa_admin_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员ID',
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '管理员名字',
  `url` varchar(1500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '操作页面',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '日志标题',
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '内容',
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT 'IP',
  `useragent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT 'User-Agent',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `name`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 247 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_area
-- ----------------------------
DROP TABLE IF EXISTS `fa_area`;
CREATE TABLE `fa_area`  (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(10) NULL DEFAULT NULL COMMENT '父id',
  `shortname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '简称',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '名称',
  `mergename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '全称',
  `level` tinyint(4) NULL DEFAULT NULL COMMENT '层级:1=省,2=市,3=区/县',
  `pinyin` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '拼音',
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '长途区号',
  `zip` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '邮编',
  `first` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '首字母',
  `lng` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '经度',
  `lat` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '纬度',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `pid`(`pid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3749 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '地区表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_attachment
-- ----------------------------
DROP TABLE IF EXISTS `fa_attachment`;
CREATE TABLE `fa_attachment`  (
  `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '类别',
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员ID',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员ID',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '物理路径',
  `imagewidth` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '宽度',
  `imageheight` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '高度',
  `imagetype` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '图片类型',
  `imageframes` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '图片帧数',
  `filename` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '文件名称',
  `filesize` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小',
  `mimetype` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT 'mime类型',
  `extparam` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '透传数据',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建日期',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  `uploadtime` bigint(16) NULL DEFAULT NULL COMMENT '上传时间',
  `storage` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'local' COMMENT '存储位置',
  `sha1` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '文件 sha1编码',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '附件表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `fa_auth_group`;
CREATE TABLE `fa_auth_group`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父组别',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '组名',
  `rules` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '规则ID',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '分组表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `fa_auth_group_access`;
CREATE TABLE `fa_auth_group_access`  (
  `uid` int(10) UNSIGNED NOT NULL COMMENT '会员ID',
  `group_id` int(10) UNSIGNED NOT NULL COMMENT '级别ID',
  UNIQUE INDEX `uid_group_id`(`uid`, `group_id`) USING BTREE,
  INDEX `uid`(`uid`) USING BTREE,
  INDEX `group_id`(`group_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '权限分组表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `fa_auth_rule`;
CREATE TABLE `fa_auth_rule`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` enum('menu','file') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'file' COMMENT 'menu为菜单,file为权限节点',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父ID',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '规则名称',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '规则名称',
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '图标',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '规则URL',
  `condition` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '条件',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '备注',
  `ismenu` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否为菜单',
  `menutype` enum('addtabs','blank','dialog','ajax') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '菜单类型',
  `extend` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '扩展属性',
  `py` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '拼音首字母',
  `pinyin` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '拼音',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) NOT NULL DEFAULT 0 COMMENT '权重',
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE,
  INDEX `pid`(`pid`) USING BTREE,
  INDEX `weigh`(`weigh`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 653 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '节点表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_category
-- ----------------------------
DROP TABLE IF EXISTS `fa_category`;
CREATE TABLE `fa_category`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父ID',
  `type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '栏目类型',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `flag` set('hot','index','recommend') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `image` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '图片',
  `keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '关键字',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '描述',
  `diyname` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '自定义名称',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) NOT NULL DEFAULT 0 COMMENT '权重',
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `weigh`(`weigh`, `id`) USING BTREE,
  INDEX `pid`(`pid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '分类表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_config
-- ----------------------------
DROP TABLE IF EXISTS `fa_config`;
CREATE TABLE `fa_config`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '变量名',
  `group` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '分组',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '变量标题',
  `tip` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '变量描述',
  `type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '类型:string,text,int,bool,array,datetime,date,file',
  `visible` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '可见条件',
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '变量值',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '变量字典数据',
  `rule` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '验证规则',
  `extend` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '扩展属性',
  `setting` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '配置',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统配置' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_ems
-- ----------------------------
DROP TABLE IF EXISTS `fa_ems`;
CREATE TABLE `fa_ems`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `event` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '事件',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '邮箱',
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '验证码',
  `times` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '验证次数',
  `ip` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT 'IP',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '邮箱验证码表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_admission
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_admission`;
CREATE TABLE `fa_fzly_admission`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type_ids` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' COMMENT '类型',
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '门票名称',
  `flag` set('hot','index','recommend') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '标志:hot=热门,recommend=推荐',
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '省市',
  `image` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '缩略图',
  `images` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '详情图',
  `tags` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '标签',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '地址',
  `address_xx` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '详细地址',
  `lon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '经度',
  `lat` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '纬度',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '简介',
  `yd_multiplejson` varchar(1500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '预定:icon=图标,intro=介绍',
  `guarantee` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '保障',
  `open_times` time NULL DEFAULT NULL COMMENT '开启时间',
  `end_times` time NULL DEFAULT NULL COMMENT '关闭时间',
  `mp_multiplejson` varchar(1500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '门票:intro=名称,label=标签,text=简述,score=来源,price=价格,line_price=价格,ys=已售',
  `score` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '评分',
  `common_nums` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '评论数',
  `weigh` int(10) NULL DEFAULT 0 COMMENT '权重',
  `status` enum('normal','hidden') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'normal' COMMENT '状态',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  `deletetime` bigint(16) NULL DEFAULT NULL COMMENT '删除时间',
  `lowest_price` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '最低价格',
  `highest_price` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '最高价格',
  `type_id` int(10) NULL DEFAULT 0 COMMENT '门票分类',
  `hot` int(10) NULL DEFAULT 0 COMMENT '热度',
  `price` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '门票价格',
  `use_rules` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '使用规则',
  `applicable_people` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '适用人群（逗号分隔）',
  `desc` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '简述',
  `view_nums` int(10) NULL DEFAULT 0 COMMENT '浏览数',
  `attractions_id` int(10) NULL DEFAULT 0 COMMENT '所属景区',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 61 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '门票表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_admission_money_log
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_admission_money_log`;
CREATE TABLE `fa_fzly_admission_money_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `attractions_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '景区id',
  `admission_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '门票id',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '支付用户id',
  `money` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '变更余额',
  `before` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '变更前余额',
  `after` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '变更后余额',
  `y` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '年',
  `m` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '月',
  `d` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '日',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '景区收益表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_admission_type
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_admission_type`;
CREATE TABLE `fa_fzly_admission_type`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '名称',
  `weigh` int(10) NULL DEFAULT 0 COMMENT '权重',
  `status` enum('normal','hidden') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'normal' COMMENT '状态',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '门票分类表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_advertising
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_advertising`;
CREATE TABLE `fa_fzly_advertising`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `url_id` int(10) NULL DEFAULT 0 COMMENT '页面地址ID',
  `image` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '图片',
  `tz_url` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '链接',
  `enddate` date NULL DEFAULT NULL COMMENT '过期时间',
  `status` enum('normal','hidden') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'normal' COMMENT '状态',
  `position` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '图片位置',
  `params` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '参数',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `url_id`(`url_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '广告位表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_advertising_log
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_advertising_log`;
CREATE TABLE `fa_fzly_advertising_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) NULL DEFAULT 0 COMMENT '用户id',
  `advertising_id` int(10) NULL DEFAULT 0 COMMENT '广告位id',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  `counts` int(10) NULL DEFAULT 0 COMMENT '点击次数',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '广告位日志表' ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for fa_fzly_app
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_app`;
CREATE TABLE `fa_fzly_app`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '分类名称',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '页面表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_app_navigation
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_app_navigation`;
CREATE TABLE `fa_fzly_app_navigation`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '标题',
  `url` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '跳转页面',
  `image` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '图标',
  `params` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '跳转参数',
  `weigh` int(10) NULL DEFAULT 0 COMMENT '权重',
  `status` enum('normal','hidden') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'normal' COMMENT '状态',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `title`(`title`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '首页导航栏表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_app_url
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_app_url`;
CREATE TABLE `fa_fzly_app_url`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type_id` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '页面类型',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '标题',
  `url` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'url',
  `params` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '跳转参数',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `type_id`(`type_id`) USING BTREE,
  INDEX `title`(`title`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '页面url表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_attractions
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_attractions`;
CREATE TABLE `fa_fzly_attractions`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '名称',
  `type_id` int(10) NULL DEFAULT 0 COMMENT '分类',
  `hot` int(10) NULL DEFAULT 0 COMMENT '热度',
  `status` enum('1','2','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '审核状态:0=未审核,1=已审核,2=审核失败',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  `image` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '缩略图',
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '省市',
  `desc` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '简述',
  `money` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '收益',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '姓名',
  `tel` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '手机号',
  `card` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '身份证号',
  `yy_image` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '营业执照',
  `refuse` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '审核拒绝原因',
  `user_id` int(10) NULL DEFAULT 0 COMMENT '用户',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '景点表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_attractions_type
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_attractions_type`;
CREATE TABLE `fa_fzly_attractions_type`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '标题',
  `weigh` int(10) NULL DEFAULT 0 COMMENT '权重',
  `status` enum('normal','hidden') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'normal' COMMENT '状态',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '景点分类表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_comment
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_comment`;
CREATE TABLE `fa_fzly_comment`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) NULL DEFAULT 0 COMMENT '会员ID',
  `type` int(10) NULL DEFAULT 0 COMMENT '类型:1=动态,2=攻略/游记/美食,3=门票',
  `con_id` int(10) NULL DEFAULT 0 COMMENT '内容ID',
  `p_id` int(10) NULL DEFAULT 0 COMMENT '父ID',
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '评论内容',
  `u_id` int(10) NULL DEFAULT 0 COMMENT '回复人id',
  `is_del` tinyint(1) NULL DEFAULT 0 COMMENT '是否删除',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '评论表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_content
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_content`;
CREATE TABLE `fa_fzly_content`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `jqt_id` int(10) NULL DEFAULT 0 COMMENT '景区类型',
  `type` enum('1','2','3') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '状态:1=攻略,2=游记,3=美食',
  `user_id` int(10) NULL DEFAULT 0 COMMENT '发布用户',
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '发布地址',
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '标题',
  `image` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '缩略图',
  `images` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '详情图',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '内容',
  `view_nums` int(10) NULL DEFAULT 0 COMMENT '浏览数',
  `dz_nums` int(10) NULL DEFAULT 0 COMMENT '点赞数',
  `sc_nums` int(10) NULL DEFAULT 0 COMMENT '收藏数',
  `common_nums` int(10) NULL DEFAULT 0 COMMENT '评论数',
  `flag` set('hot','index','recommend') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '标志:hot=热门,recommend=推荐',
  `status` enum('1','2','3','4','5') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '状态:1=草稿,2=未审核,3=审核通过,4=审核拒绝,5=已下架',
  `weigh` int(10) NULL DEFAULT 0 COMMENT '权重',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  `mp_id` int(10) NULL DEFAULT 0 COMMENT '门票',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 66 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '攻略/游记/美食表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_coupon
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_coupon`;
CREATE TABLE `fa_fzly_coupon`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '标题',
  `image` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '图片',
  `with_amount` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '所需金额条件',
  `used_amount` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '抵扣金额',
  `quota` int(10) NULL DEFAULT 0 COMMENT '发券数量',
  `start_time` bigint(16) NULL DEFAULT NULL COMMENT '生效时间',
  `end_time` bigint(16) NULL DEFAULT NULL COMMENT '失效时间',
  `draw_range` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '可领取时间段',
  `remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '说明',
  `weigh` int(10) NULL DEFAULT 0 COMMENT '权重',
  `status` enum('normal','hidden') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'normal' COMMENT '状态',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  `deletetime` bigint(16) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '优惠券信息表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_coupon_receive
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_coupon_receive`;
CREATE TABLE `fa_fzly_coupon_receive`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) NULL DEFAULT 0 COMMENT '会员ID',
  `coupon_id` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '优惠券ID',
  `money` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '优惠券金额',
  `create_time` bigint(16) NULL DEFAULT NULL COMMENT '领取时间',
  `state` enum('0','1','-1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '状态值:1为已使用，0为已领取未使用，-1为已过期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '优惠券领取记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_custom_report
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_custom_report`;
CREATE TABLE `fa_fzly_custom_report`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '报表标题',
  `data_source` enum('sales','stock','visitor','device') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '数据来源',
  `fields` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '报表字段(JSON)',
  `filters` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '过滤条件(JSON)',
  `sort` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '排序条件(JSON)',
  `user_id` int(10) NOT NULL COMMENT '创建用户ID',
  `is_public` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否公开:0=否,1=是',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态:0=禁用,1=启用',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_user`(`user_id`) USING BTREE,
  INDEX `idx_source`(`data_source`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '自定义报表配置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_data_change_log
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_data_change_log`;
CREATE TABLE `fa_fzly_data_change_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员ID',
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '管理员用户名',
  `table_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '数据表名',
  `table_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '数据ID',
  `action` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作：add-新增，edit-编辑，del-删除',
  `old_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '旧数据',
  `new_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '新数据',
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '操作IP',
  `createtime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '操作时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_table`(`table_name`, `table_id`) USING BTREE,
  INDEX `idx_admin_id`(`admin_id`) USING BTREE,
  INDEX `idx_createtime`(`createtime`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '数据变更日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_data_sync_log
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_data_sync_log`;
CREATE TABLE `fa_fzly_data_sync_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `module` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '模块名称',
  `sync_time` bigint(16) NOT NULL COMMENT '同步时间',
  `data_count` int(10) NULL DEFAULT 0 COMMENT '同步数据量',
  `status` enum('success','fail') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '同步状态',
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '同步信息',
  `duration` int(10) NULL DEFAULT 0 COMMENT '同步耗时(毫秒)',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_module`(`module`) USING BTREE,
  INDEX `idx_time`(`sync_time`) USING BTREE,
  INDEX `idx_status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '数据同步日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_device_report
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_device_report`;
CREATE TABLE `fa_fzly_device_report`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `device_id` int(10) NOT NULL COMMENT '设备ID',
  `device_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '设备名称',
  `device_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '设备类型',
  `date` datetime NOT NULL COMMENT '日期时间',
  `status` enum('normal','warning','error') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '设备状态',
  `operation_time` int(10) NULL DEFAULT 0 COMMENT '运行时长(分钟)',
  `fault_time` int(10) NULL DEFAULT 0 COMMENT '故障时长(分钟)',
  `fault_count` int(10) NULL DEFAULT 0 COMMENT '故障次数',
  `data_count` int(10) NULL DEFAULT 0 COMMENT '处理数据量',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_device`(`device_id`) USING BTREE,
  INDEX `idx_date`(`date`) USING BTREE,
  INDEX `idx_status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '设备运行报表数据表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_distribution_grade
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_distribution_grade`;
CREATE TABLE `fa_fzly_distribution_grade`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '等级名称',
  `image` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '等级icon',
  `one_level` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '一级分销比例',
  `two_level` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '二级分销比例',
  `team_xx` int(10) NULL DEFAULT 0 COMMENT '升级条件-团队所需人数',
  `amount` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '升级条件-下级支付金额',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  `deletetime` bigint(16) NULL DEFAULT NULL COMMENT '删除时间',
  `weigh` int(10) NULL DEFAULT 0 COMMENT '权重',
  `state` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '状态值:0=禁用,1=正常',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '分销等级表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_distribution_grade_log
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_distribution_grade_log`;
CREATE TABLE `fa_fzly_distribution_grade_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) NULL DEFAULT 0 COMMENT '会员ID',
  `before` int(10) NULL DEFAULT 0 COMMENT '变更前等级',
  `after` int(10) NULL DEFAULT 0 COMMENT '变更后等级',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  `flag` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '操作人 系统,/人工',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '分销等级变更记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_distribution_log
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_distribution_log`;
CREATE TABLE `fa_fzly_distribution_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) NULL DEFAULT 0 COMMENT '受益人',
  `pay_user_id` int(10) NULL DEFAULT 0 COMMENT '支付者',
  `order_id` int(10) NULL DEFAULT 0 COMMENT '订单ID',
  `pay_amount` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '支付金额',
  `pay_time` bigint(16) NULL DEFAULT NULL COMMENT '支付时间',
  `distribution_amount` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '分佣金额',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  `status` enum('1','2') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '状态1=待入账2=已入账',
  `lesson_id` int(10) NULL DEFAULT 0 COMMENT '课程id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '分销明细表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_distribution_share
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_distribution_share`;
CREATE TABLE `fa_fzly_distribution_share`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) NULL DEFAULT 0 COMMENT '会员ID',
  `p_id` int(10) NULL DEFAULT 0 COMMENT '会员ID',
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '说明',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '分享邀请表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_distribution_tghb
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_distribution_tghb`;
CREATE TABLE `fa_fzly_distribution_tghb`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `img` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '图片地址',
  `createtime` bigint(16) NOT NULL COMMENT '创建时间',
  `imgx` int(11) NOT NULL COMMENT '图片x轴',
  `imgy` int(11) NOT NULL COMMENT '图片y轴',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '推广海报图片' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_distribution_withdraw_log
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_distribution_withdraw_log`;
CREATE TABLE `fa_fzly_distribution_withdraw_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) NULL DEFAULT 0 COMMENT '用户',
  `withdraw_money` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '提现金额',
  `leave_withdraw_money` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '剩余可提现金额',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '提现时间',
  `type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '提现方式',
  `status` enum('1','2','3','4') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '状态:1=待审核,2=审核拒绝,3=待到账,4=已到账',
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '审核拒绝原因',
  `draw_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '提现订单号',
  `batch_id` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '第三方回调单号',
  `bank` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '银行卡',
  `ali` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '支付宝',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '提现记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_evaluate
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_evaluate`;
CREATE TABLE `fa_fzly_evaluate`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` enum('1','2','3','4','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '类型:1=门票,2=导游产品',
  `xx_json` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '打星标签:value=名称',
  `tag_json` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '评价标签:value=名称',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '评价管理表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_feedback
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_feedback`;
CREATE TABLE `fa_fzly_feedback`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) NULL DEFAULT NULL COMMENT '用户id',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '内容',
  `tel` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '联系方式',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '意见反馈表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_finance_expense
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_finance_expense`;
CREATE TABLE `fa_fzly_finance_expense`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `date` date NOT NULL COMMENT '日期',
  `expense_type` enum('purchase','ota_commission','equipment_maintenance','salary','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '支出类型',
  `amount` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '支出金额',
  `voucher_id` int(10) NULL DEFAULT NULL COMMENT '关联凭证ID',
  `supplier` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '供应商',
  `status` enum('pending','confirmed','canceled') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'confirmed' COMMENT '状态',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '备注',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_date`(`date`) USING BTREE,
  INDEX `idx_type`(`expense_type`) USING BTREE,
  INDEX `idx_voucher`(`voucher_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '支出记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_finance_fee
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_finance_fee`;
CREATE TABLE `fa_fzly_finance_fee`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `date` date NOT NULL COMMENT '日期',
  `payment_channel` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '支付渠道',
  `order_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '订单号',
  `amount` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '订单金额',
  `fee_rate` decimal(5, 2) NULL DEFAULT 0.00 COMMENT '手续费率(%)',
  `fee_amount` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '手续费金额',
  `status` enum('pending','confirmed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'confirmed' COMMENT '状态',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_date`(`date`) USING BTREE,
  INDEX `idx_channel`(`payment_channel`) USING BTREE,
  INDEX `idx_order`(`order_no`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '手续费记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_finance_income
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_finance_income`;
CREATE TABLE `fa_fzly_finance_income`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `date` date NOT NULL COMMENT '日期',
  `income_type` enum('ticket','product','food','timed_service','parking') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '收入类型',
  `channel` enum('offline','ota','mini_program','official','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '渠道',
  `order_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '订单号',
  `amount` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '收入金额',
  `status` enum('pending','confirmed','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'confirmed' COMMENT '状态',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '备注',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_date`(`date`) USING BTREE,
  INDEX `idx_type`(`income_type`) USING BTREE,
  INDEX `idx_channel`(`channel`) USING BTREE,
  INDEX `idx_order`(`order_no`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '收入记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_finance_profit
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_finance_profit`;
CREATE TABLE `fa_fzly_finance_profit`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `period_type` enum('daily','monthly','yearly') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '周期类型',
  `period` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '周期值(如2023-10, 2023)',
  `total_income` decimal(12, 2) NOT NULL DEFAULT 0.00 COMMENT '总收入',
  `total_expense` decimal(12, 2) NOT NULL DEFAULT 0.00 COMMENT '总支出',
  `gross_profit` decimal(12, 2) NOT NULL DEFAULT 0.00 COMMENT '毛利润',
  `net_profit` decimal(12, 2) NOT NULL DEFAULT 0.00 COMMENT '净利润',
  `profit_rate` decimal(5, 2) NULL DEFAULT 0.00 COMMENT '利润率(%)',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_period`(`period_type`, `period`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '利润核算表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_finance_reconciliation
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_finance_reconciliation`;
CREATE TABLE `fa_fzly_finance_reconciliation`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `reconciliation_type` enum('ota','supplier','bank') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '对账类型',
  `partner` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '合作方',
  `start_date` date NOT NULL COMMENT '开始日期',
  `end_date` date NOT NULL COMMENT '结束日期',
  `system_amount` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '系统金额',
  `partner_amount` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '合作方金额',
  `difference` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '差额',
  `status` enum('pending','reconciling','completed','abnormal') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending' COMMENT '状态',
  `file_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '对账单文件路径',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '备注',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_type`(`reconciliation_type`) USING BTREE,
  INDEX `idx_partner`(`partner`) USING BTREE,
  INDEX `idx_status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '对账单表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_finance_reconciliation_diff
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_finance_reconciliation_diff`;
CREATE TABLE `fa_fzly_finance_reconciliation_diff`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `reconciliation_id` int(10) NOT NULL COMMENT '对账单ID',
  `order_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '订单号',
  `system_amount` decimal(10, 2) NULL DEFAULT NULL COMMENT '系统金额',
  `partner_amount` decimal(10, 2) NULL DEFAULT NULL COMMENT '合作方金额',
  `difference` decimal(10, 2) NULL DEFAULT NULL COMMENT '差额',
  `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '差异原因',
  `status` enum('unhandled','handled') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'unhandled' COMMENT '状态',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_reconciliation`(`reconciliation_id`) USING BTREE,
  INDEX `idx_order`(`order_no`) USING BTREE,
  INDEX `idx_status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '对账单差异表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_finance_report_config
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_finance_report_config`;
CREATE TABLE `fa_fzly_finance_report_config`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `report_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '报表类型',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '报表标题',
  `fields` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '报表字段(JSON)',
  `filters` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '筛选条件(JSON)',
  `sort` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '排序规则(JSON)',
  `user_id` int(10) NOT NULL COMMENT '创建用户',
  `is_public` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否公开:0=否,1=是',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态:0=禁用,1=启用',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_type`(`report_type`) USING BTREE,
  INDEX `idx_user`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '财务报表配置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_finance_voucher
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_finance_voucher`;
CREATE TABLE `fa_fzly_finance_voucher`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `voucher_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '凭证编号',
  `voucher_type` enum('invoice','receipt','contract') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '凭证类型',
  `amount` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '凭证金额',
  `file_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '凭证文件路径',
  `status` enum('valid','invalid') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'valid' COMMENT '状态',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_voucher_no`(`voucher_no`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '财务凭证表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_guide_money_log
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_guide_money_log`;
CREATE TABLE `fa_fzly_guide_money_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `gudie_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '导游id',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '支付用户id',
  `money` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '变更余额',
  `before` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '变更前余额',
  `after` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '变更后余额',
  `y` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '年',
  `m` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '月',
  `d` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '日',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '导游收益表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_guide_product
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_guide_product`;
CREATE TABLE `fa_fzly_guide_product`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `image` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '缩略图',
  `user_id` int(10) NULL DEFAULT 0 COMMENT '导游',
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '产品标题',
  `jq_title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '景区地点',
  `zm_image` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '景区合作证明',
  `type_id` int(10) NULL DEFAULT 0 COMMENT '产品分类',
  `model` set('1','2','3','4') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '车型:1=舒适五座,2=商务七座,3=中 大巴车,4=暂无',
  `duration` set('1','2','3','4','5') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '时长:1=八小时,2=4小时,3=2小时,4=1小时,5=暂无',
  `yd_multiplejson` varchar(1500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '预订:icon=图标,intro=介绍',
  `guarantee` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '保障',
  `cp_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '产品特色',
  `tw_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '图文详情',
  `fy_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '费用说明',
  `tg_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '退改原则',
  `tg_multiplejson` varchar(1500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '退改规则:time=取消时间,fy=损失费用',
  `status` enum('1','2','3','4') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '状态:1=未审核,2=审核通过,3=审核拒绝,4=下架',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  `price` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '价格',
  `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '拒绝原因',
  `weigh` int(10) NULL DEFAULT 0 COMMENT '权重',
  `view_nums` int(10) NULL DEFAULT 0 COMMENT '浏览数',
  `sc_nums` int(10) NULL DEFAULT 0 COMMENT '收藏数',
  `score` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '评分',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 68 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '导游产品表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_guide_type
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_guide_type`;
CREATE TABLE `fa_fzly_guide_type`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '标题',
  `weigh` int(10) NULL DEFAULT 0 COMMENT '权重',
  `status` enum('normal','hidden') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'normal' COMMENT '状态',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '导游产品分类表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_house
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_house`;
CREATE TABLE `fa_fzly_house`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `brand_id` int(10) NOT NULL DEFAULT 0 COMMENT '品牌id',
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '酒店名称',
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '省市',
  `image` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '缩略图',
  `stay_type` set('1','2','3','4','5','6','7') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '住宿类型:1=青年旅社,2=公寓,3=民宿,4=度假酒店,5=别墅,6=主题酒店,7=精选酒店',
  `grade` enum('1','2','3','4') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '档次:1=经济,2=舒适,3=高档,4=豪华',
  `facilities` set('1','2','3','4','5') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '热门设施:1=温泉,2=厨房,3=停车场,4=洗衣房,5=wifi',
  `images` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '详情图',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '地址',
  `address_xx` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '详细地址',
  `lon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '经度',
  `lat` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '纬度',
  `multiplejson_yd` varchar(1500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '预订:icon=图标,intro=介绍',
  `guarantee` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '保障',
  `multiplejson_md` varchar(1500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '卖点-设施:icon=图标,label=标签,intro=介绍',
  `weigh` int(10) NULL DEFAULT 0 COMMENT '权重',
  `status` enum('normal','hidden') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'normal' COMMENT '状态',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  `deletetime` bigint(16) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '酒店表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_house_brand
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_house_brand`;
CREATE TABLE `fa_fzly_house_brand`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '品牌名称',
  `weigh` int(10) NULL DEFAULT 0 COMMENT '权重',
  `switch` tinyint(1) NULL DEFAULT 1 COMMENT '开关',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '酒店品牌表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_login_log
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_login_log`;
CREATE TABLE `fa_fzly_login_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员ID',
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '管理员用户名',
  `login_type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '登录类型：1-正常登录，2-退出登录，3-登录失败',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态：1-成功，0-失败',
  `message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '登录信息',
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '登录IP',
  `location` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT 'IP所在地',
  `useragent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT 'User-Agent',
  `logintime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '登录时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_admin_id`(`admin_id`) USING BTREE,
  INDEX `idx_logintime`(`logintime`) USING BTREE,
  INDEX `idx_status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '登录日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_message_log
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_message_log`;
CREATE TABLE `fa_fzly_message_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) NULL DEFAULT 0 COMMENT '用户',
  `point` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '推送消息节点',
  `status` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '状态 0=失败 1=成功',
  `result` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '返回结果',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '消息推送日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_message_push
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_message_push`;
CREATE TABLE `fa_fzly_message_push`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `message_type` enum('1','2','3','4','5') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '模版分类1=支付通知模版,2=订单核销通知模版',
  `masterplate` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '模版消息ID',
  `multiplejson` varchar(1500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '详细内容:title=字段名,keyword=微信关键字,value=介绍',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '消息推送配置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_order
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_order`;
CREATE TABLE `fa_fzly_order`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `order_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '订单编号',
  `order_type` enum('1','2','3','4','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '订单类型:1=门票订单,2=导游订单',
  `user_id` int(10) NULL DEFAULT 0 COMMENT '用户',
  `goods_id` int(10) NULL DEFAULT 0 COMMENT '商品id',
  `travel_ids` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '出行人',
  `coupon_id` int(10) NULL DEFAULT 0 COMMENT '优惠券ID',
  `status` enum('1','2','3','4','5','6') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '订单状态:1=待付款,2=已付款,3=已核销,4=待退款,5=已取消',
  `price` decimal(10, 2) NULL DEFAULT NULL COMMENT '商品单价',
  `count` int(10) NULL DEFAULT NULL COMMENT '购买数量',
  `order_amount_total` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '实际付款金额',
  `out_trade_no` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '三方支付单号',
  `remark` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '订单备注',
  `yd_dsj` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '预约时长',
  `yd_time` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '预订日期',
  `yd_model` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '预约车型',
  `pay_time` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '支付时间',
  `out_refund_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '退款单号',
  `refund_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '三方退款单号',
  `apply` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '退款申请原因',
  `last_status` int(10) NULL DEFAULT 0 COMMENT '退款前一步订单状态',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  `guide_id` int(10) NULL DEFAULT 0 COMMENT '导游id',
  `is_hx` int(10) NULL DEFAULT 0 COMMENT '是否已核销',
  `attractions_id` int(10) NULL DEFAULT 0 COMMENT '景区id(用于统计管理账号下的订单)',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '订单表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_order_detail
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_order_detail`;
CREATE TABLE `fa_fzly_order_detail`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `order_id` int(100) NULL DEFAULT NULL COMMENT '订单id',
  `travel_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '出行人id',
  `status` enum('1','2','3','4','5','6') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '订单状态:1=待核销,2=已核销,3=已取消',
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '订单核销码',
  `code_image` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '核销二维码',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '订单出行人核销表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_order_evaluate
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_order_evaluate`;
CREATE TABLE `fa_fzly_order_evaluate`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `order_id` int(10) NULL DEFAULT NULL COMMENT '订单id',
  `order_type` enum('1','2','3','4','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '订单类型:1=门票订单,2=导游订单',
  `user_id` int(10) NULL DEFAULT 0 COMMENT '用户',
  `guide_id` int(10) NULL DEFAULT 0 COMMENT '导游id',
  `goods_id` int(10) NULL DEFAULT 0 COMMENT '商品id',
  `score` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '评分',
  `evaluate` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '评价内容',
  `is_nm` int(10) NULL DEFAULT 0 COMMENT '是否匿名',
  `images` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '详情图',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  `xx_json` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '打星标签',
  `tag_json` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '评价标签',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '订单评价表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_pact
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_pact`;
CREATE TABLE `fa_fzly_pact`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '标题',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '内容',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) NULL DEFAULT 0 COMMENT '权重',
  `switch` tinyint(1) NULL DEFAULT 1 COMMENT '开关',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '协议&政策表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_product
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_product`;
CREATE TABLE `fa_fzly_product`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `category_id` int(10) UNSIGNED NOT NULL COMMENT '分类ID',
  `supplier_id` int(10) UNSIGNED NOT NULL COMMENT '供应商ID',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '商品名称',
  `sn` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '商品编号',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '商品图片',
  `price` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '销售单价',
  `purchase_price` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '采购单价',
  `spec` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '规格',
  `unit` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '单位',
  `type` enum('physical','virtual') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'physical' COMMENT '商品类型：physical=实物，virtual=虚拟',
  `status` enum('normal','hidden') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'normal' COMMENT '状态',
  `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '备注',
  `createtime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updatetime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `sn`(`sn`) USING BTREE,
  INDEX `category_id`(`category_id`) USING BTREE,
  INDEX `supplier_id`(`supplier_id`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '商品表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_product_category
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_product_category`;
CREATE TABLE `fa_fzly_product_category`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '分类名称',
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父分类ID',
  `level` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '分类级别',
  `sort` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `status` enum('normal','hidden') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'normal' COMMENT '状态',
  `createtime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updatetime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `parent_id`(`parent_id`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '商品分类表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_profit_detail
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_profit_detail`;
CREATE TABLE `fa_fzly_profit_detail`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `reconciliation_id` int(10) NOT NULL COMMENT '对账ID',
  `product_id` int(10) NOT NULL COMMENT '产品ID',
  `product_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '产品名称',
  `sales_num` int(10) NOT NULL DEFAULT 0 COMMENT '销售数量',
  `sales_amount` decimal(12, 2) NOT NULL DEFAULT 0.00 COMMENT '销售金额',
  `cost_price` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '成本单价',
  `total_cost` decimal(12, 2) NOT NULL DEFAULT 0.00 COMMENT '总成本',
  `profit` decimal(12, 2) NOT NULL DEFAULT 0.00 COMMENT '利润',
  `profit_rate` decimal(5, 2) NOT NULL DEFAULT 0.00 COMMENT '利润率(%)',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_reconciliation_id`(`reconciliation_id`) USING BTREE,
  INDEX `idx_product_id`(`product_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '利润核算明细表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_promotion
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_promotion`;
CREATE TABLE `fa_fzly_promotion`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '优惠活动名称',
  `type` enum('discount','full_reduction','buy_gift','member_price') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '优惠类型：discount=折扣券,full_reduction=满减券,buy_gift=买赠活动,member_price=会员专属价',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '活动描述',
  `start_time` bigint(16) NOT NULL COMMENT '活动开始时间',
  `end_time` bigint(16) NOT NULL COMMENT '活动结束时间',
  `status` enum('normal','hidden','expired') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'normal' COMMENT '状态：normal=正常,hidden=暂停,expired=已过期',
  `cover_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '活动封面图',
  `total_receive` int(10) NULL DEFAULT 0 COMMENT '总领取次数',
  `total_use` int(10) NULL DEFAULT 0 COMMENT '总使用次数',
  `total_discount` decimal(12, 2) NULL DEFAULT 0.00 COMMENT '总优惠金额',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  `deletetime` bigint(16) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '优惠策略表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_promotion_channel
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_promotion_channel`;
CREATE TABLE `fa_fzly_promotion_channel`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `promotion_id` int(10) UNSIGNED NOT NULL COMMENT '关联优惠活动ID',
  `channel` enum('mini_program','offline_window','ota','self_operated') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '发放渠道：mini_program=小程序,offline_window=线下窗口,ota=OTA分销,self_operated=自营',
  `channel_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '渠道名称',
  `start_time` bigint(16) NULL DEFAULT NULL COMMENT '渠道发放开始时间',
  `end_time` bigint(16) NULL DEFAULT NULL COMMENT '渠道发放结束时间',
  `status` enum('normal','hidden') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'normal' COMMENT '状态',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `promotion_id`(`promotion_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '优惠发放渠道表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_promotion_product
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_promotion_product`;
CREATE TABLE `fa_fzly_promotion_product`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `promotion_id` int(10) UNSIGNED NOT NULL COMMENT '关联优惠活动ID',
  `product_type` enum('ticket','goods') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '产品类型：ticket=门票,goods=商品',
  `product_id` int(10) UNSIGNED NOT NULL COMMENT '产品ID',
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '产品名称',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `promotion_id`(`promotion_id`) USING BTREE,
  INDEX `product_type_id`(`product_type`, `product_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '优惠适用产品表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_promotion_rule
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_promotion_rule`;
CREATE TABLE `fa_fzly_promotion_rule`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `promotion_id` int(10) UNSIGNED NOT NULL COMMENT '关联优惠活动ID',
  `condition_amount` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '触发金额条件（满多少）',
  `discount_value` decimal(10, 2) NOT NULL COMMENT '优惠值（折扣率/减免金额/赠品ID）',
  `limit_per_person` int(10) NULL DEFAULT 1 COMMENT '每人限领/用次数',
  `total_quota` int(10) NULL DEFAULT 0 COMMENT '总数量限制（0为无限制）',
  `member_level` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '适用会员等级（JSON）',
  `use_limit` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '使用限制说明',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `promotion_id`(`promotion_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '优惠规则表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_promotion_stat
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_promotion_stat`;
CREATE TABLE `fa_fzly_promotion_stat`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `promotion_id` int(10) UNSIGNED NOT NULL COMMENT '关联优惠活动ID',
  `stat_date` date NOT NULL COMMENT '统计日期',
  `receive_count` int(10) NULL DEFAULT 0 COMMENT '当日领取次数',
  `use_count` int(10) NULL DEFAULT 0 COMMENT '当日使用次数',
  `sales_increase` int(10) NULL DEFAULT 0 COMMENT '销量增长数',
  `discount_amount` decimal(12, 2) NULL DEFAULT 0.00 COMMENT '当日优惠金额',
  `verification_rate` decimal(5, 2) NULL DEFAULT 0.00 COMMENT '核销率(%)',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `promotion_date`(`promotion_id`, `stat_date`) USING BTREE,
  INDEX `stat_date`(`stat_date`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '优惠统计数据表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_purchase_order
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_purchase_order`;
CREATE TABLE `fa_fzly_purchase_order`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `order_sn` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '订单编号',
  `supplier_id` int(10) UNSIGNED NOT NULL COMMENT '供应商ID',
  `total_amount` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '订单总金额',
  `purchase_time` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '采购时间',
  `arrival_time` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '到货时间',
  `status` enum('pending','received','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending' COMMENT '状态：pending=待收货，received=已收货，cancelled=已取消',
  `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '备注',
  `operate_id` int(10) UNSIGNED NOT NULL COMMENT '操作人ID',
  `operate_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作人姓名',
  `createtime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updatetime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `order_sn`(`order_sn`) USING BTREE,
  INDEX `supplier_id`(`supplier_id`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '采购订单表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_purchase_order_item
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_purchase_order_item`;
CREATE TABLE `fa_fzly_purchase_order_item`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `order_id` int(10) UNSIGNED NOT NULL COMMENT '订单ID',
  `product_id` int(10) UNSIGNED NOT NULL COMMENT '商品ID',
  `quantity` int(10) UNSIGNED NOT NULL COMMENT '采购数量',
  `price` decimal(10, 2) NOT NULL COMMENT '采购单价',
  `amount` decimal(10, 2) NOT NULL COMMENT '金额',
  `received_quantity` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '已收货数量',
  `createtime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updatetime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `order_id`(`order_id`) USING BTREE,
  INDEX `product_id`(`product_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '采购订单明细表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_record
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_record`;
CREATE TABLE `fa_fzly_record`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `session_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会话ID',
  `sender_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '发送人ID',
  `receive_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '接收人ID',
  `message_type` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '消息类型:0=富文本,1=图片',
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '消息',
  `status` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' COMMENT '状态:0=未读,1=已读',
  `createtime` bigint(16) UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
  `is_del_sender` int(10) NULL DEFAULT 0 COMMENT '用户1是否删除',
  `is_del_receive` int(10) NULL DEFAULT 0 COMMENT '用户2是否删除',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '聊天记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_refund_order
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_refund_order`;
CREATE TABLE `fa_fzly_refund_order`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `refund_no` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '退单号',
  `order_id` int(10) UNSIGNED NOT NULL COMMENT '关联订单ID',
  `order_no` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '原订单号',
  `user_id` int(10) UNSIGNED NOT NULL COMMENT '用户ID',
  `order_type` tinyint(1) NOT NULL COMMENT '订单类型(1:门票订单,2:导游订单)',
  `refund_amount` decimal(10, 2) NOT NULL COMMENT '退款金额',
  `fee_amount` decimal(10, 2) NOT NULL COMMENT '手续费金额',
  `actual_amount` decimal(10, 2) NOT NULL COMMENT '实际退款金额',
  `refund_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '退款原因',
  `refund_type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '退款方式(1:原路返回)',
  `status` tinyint(1) NOT NULL COMMENT '状态(1:申请中,2:已同意,3:已拒绝,4:已完成,5:失败)',
  `rule_id` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '适用规则ID',
  `admin_id` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '处理管理员ID',
  `handle_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '处理时间',
  `handle_note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '处理备注',
  `transaction_id` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '支付平台交易号',
  `refund_transaction_id` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '支付平台退款号',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uniq_refund_no`(`refund_no`) USING BTREE,
  INDEX `idx_order_id`(`order_id`) USING BTREE,
  INDEX `idx_user_id`(`user_id`) USING BTREE,
  INDEX `idx_status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1001 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '退单记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_refund_rule
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_refund_rule`;
CREATE TABLE `fa_fzly_refund_rule`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `order_type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '订单类型(1:门票订单,2:导游订单)',
  `time_range` int(10) NOT NULL COMMENT '时间范围(分钟)',
  `fee_rate` decimal(5, 2) NOT NULL COMMENT '手续费比例(%)',
  `min_fee` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '最低手续费',
  `max_fee` decimal(10, 2) NOT NULL DEFAULT 99999.99 COMMENT '最高手续费',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态(1:启用,0:禁用)',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_order_type`(`order_type`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '退单规则配置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_report_schedule
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_report_schedule`;
CREATE TABLE `fa_fzly_report_schedule`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `report_type` enum('sales','stock','visitor','device') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '报表类型',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '标题',
  `frequency` enum('daily','weekly','monthly') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '发送频率',
  `send_time` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '发送时间',
  `receivers` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '接收邮箱(JSON)',
  `format` enum('excel','pdf') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'excel' COMMENT '文件格式',
  `params` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '报表参数(JSON)',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态:0=禁用,1=启用',
  `last_send_time` bigint(16) NULL DEFAULT NULL COMMENT '最后发送时间',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_type`(`report_type`) USING BTREE,
  INDEX `idx_status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '报表定时发送配置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_revenue_log
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_revenue_log`;
CREATE TABLE `fa_fzly_revenue_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) NULL DEFAULT 0 COMMENT '导游',
  `pay_user_id` int(10) NULL DEFAULT 0 COMMENT '支付用户',
  `order_no` int(10) NULL DEFAULT 0 COMMENT '订单号',
  `pay_amount` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '支付金额',
  `pay_time` bigint(16) NULL DEFAULT NULL COMMENT '支付时间',
  `status` enum('1','2','3') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '状态:1=待入账,2=已入账,3=已退款',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '收入明细表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_sales_order
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_sales_order`;
CREATE TABLE `fa_fzly_sales_order`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `order_sn` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '订单编号',
  `channel` enum('ticket_window','restaurant','online') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '销售渠道：ticket_window=售票窗口，restaurant=餐饮，online=线上',
  `total_amount` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '订单总金额',
  `status` enum('pending','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending' COMMENT '状态：pending=待完成，completed=已完成，cancelled=已取消',
  `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '备注',
  `operate_id` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '操作人ID',
  `operate_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '操作人姓名',
  `createtime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updatetime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `order_sn`(`order_sn`) USING BTREE,
  INDEX `channel`(`channel`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '销售订单表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_sales_order_item
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_sales_order_item`;
CREATE TABLE `fa_fzly_sales_order_item`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `order_id` int(10) UNSIGNED NOT NULL COMMENT '订单ID',
  `product_id` int(10) UNSIGNED NOT NULL COMMENT '商品ID',
  `quantity` int(10) UNSIGNED NOT NULL COMMENT '销售数量',
  `price` decimal(10, 2) NOT NULL COMMENT '销售单价',
  `amount` decimal(10, 2) NOT NULL COMMENT '金额',
  `createtime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updatetime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `order_id`(`order_id`) USING BTREE,
  INDEX `product_id`(`product_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '销售订单明细表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_sales_report
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_sales_report`;
CREATE TABLE `fa_fzly_sales_report`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `date` date NOT NULL COMMENT '日期',
  `product_id` int(10) NOT NULL COMMENT '产品ID',
  `product_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '产品名称',
  `product_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '产品类型',
  `channel` enum('offline','ota','mini_program','official') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '渠道',
  `sales_num` int(10) NOT NULL DEFAULT 0 COMMENT '销售数量',
  `sales_amount` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '销售金额',
  `refund_num` int(10) NOT NULL DEFAULT 0 COMMENT '退款数量',
  `refund_amount` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '退款金额',
  `actual_sales` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '实际销售额',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_date`(`date`) USING BTREE,
  INDEX `idx_product`(`product_id`) USING BTREE,
  INDEX `idx_channel`(`channel`) USING BTREE,
  INDEX `idx_type`(`product_type`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 60001 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '销售报表数据表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_search_history
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_search_history`;
CREATE TABLE `fa_fzly_search_history`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) NULL DEFAULT 0 COMMENT '会员ID',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '搜索内容',
  `number` int(10) NULL DEFAULT NULL COMMENT '搜索次数',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '搜索历史表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_session
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_session`;
CREATE TABLE `fa_fzly_session`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户1',
  `csr_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户2',
  `createtime` bigint(16) UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
  `deletetime` bigint(16) UNSIGNED NULL DEFAULT NULL COMMENT '删除时间',
  `is_top_user` int(10) NULL DEFAULT 0 COMMENT '用户1是否置顶',
  `is_top_csr` int(10) NULL DEFAULT 0 COMMENT '用户2是否置顶',
  `is_del_user` int(10) NULL DEFAULT 0 COMMENT '用户1是否删除',
  `is_del_csr` int(10) NULL DEFAULT 0 COMMENT '用户2是否删除',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '聊天会话表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_share
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_share`;
CREATE TABLE `fa_fzly_share`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) NULL DEFAULT 0 COMMENT '会员ID',
  `p_id` int(10) NULL DEFAULT 0 COMMENT '会员ID',
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '说明',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '分享邀请表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_stock
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_stock`;
CREATE TABLE `fa_fzly_stock`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `product_id` int(10) NOT NULL COMMENT '产品ID',
  `product_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '产品名称',
  `product_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '产品类型',
  `channel` enum('offline','ota','mini_program') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '渠道:offline=线下窗口,ota=OTA,mini_program=小程序',
  `total_stock` int(10) NOT NULL DEFAULT 0 COMMENT '总库存',
  `used_stock` int(10) NOT NULL DEFAULT 0 COMMENT '已使用库存',
  `available_stock` int(10) NOT NULL DEFAULT 0 COMMENT '可用库存',
  `lock_stock` int(10) NOT NULL DEFAULT 0 COMMENT '锁定库存',
  `date` date NOT NULL COMMENT '日期',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态:0=禁用,1=正常',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_product`(`product_id`) USING BTREE,
  INDEX `idx_channel`(`channel`) USING BTREE,
  INDEX `idx_date`(`date`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '库存信息表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_stock_change_log
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_stock_change_log`;
CREATE TABLE `fa_fzly_stock_change_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `product_id` int(10) UNSIGNED NOT NULL COMMENT '商品ID',
  `type` enum('purchase','sale','adjust','check') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '变动类型：purchase=采购入库，sale=销售出库，adjust=调整，check=盘点',
  `related_id` int(10) UNSIGNED NOT NULL COMMENT '关联ID（订单ID或盘点ID等）',
  `quantity` int(11) NOT NULL COMMENT '变动数量（正数为增加，负数为减少）',
  `before_quantity` int(10) UNSIGNED NOT NULL COMMENT '变动前库存',
  `after_quantity` int(10) UNSIGNED NOT NULL COMMENT '变动后库存',
  `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '备注',
  `operate_id` int(10) UNSIGNED NOT NULL COMMENT '操作人ID',
  `operate_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作人姓名',
  `createtime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `product_id`(`product_id`) USING BTREE,
  INDEX `type`(`type`) USING BTREE,
  INDEX `createtime`(`createtime`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '库存变动记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_stock_check
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_stock_check`;
CREATE TABLE `fa_fzly_stock_check`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `check_sn` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '盘点编号',
  `check_time` int(10) UNSIGNED NOT NULL COMMENT '盘点时间',
  `status` enum('pending','completed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending' COMMENT '状态：pending=进行中，completed=已完成',
  `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '备注',
  `operate_id` int(10) UNSIGNED NOT NULL COMMENT '操作人ID',
  `operate_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作人姓名',
  `createtime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updatetime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `check_sn`(`check_sn`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '盘点记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_stock_check_item
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_stock_check_item`;
CREATE TABLE `fa_fzly_stock_check_item`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `check_id` int(10) UNSIGNED NOT NULL COMMENT '盘点ID',
  `product_id` int(10) UNSIGNED NOT NULL COMMENT '商品ID',
  `system_quantity` int(10) UNSIGNED NOT NULL COMMENT '系统库存数量',
  `actual_quantity` int(10) UNSIGNED NOT NULL COMMENT '实际盘点数量',
  `diff_quantity` int(11) NOT NULL COMMENT '差异数量',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '差异原因',
  `createtime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updatetime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `check_id`(`check_id`) USING BTREE,
  INDEX `product_id`(`product_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '盘点明细记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_stock_freeze_log
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_stock_freeze_log`;
CREATE TABLE `fa_fzly_stock_freeze_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `stock_id` int(10) UNSIGNED NOT NULL COMMENT '库存ID',
  `time_slot_id` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '分时库存ID（0表示不分时）',
  `order_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '订单号',
  `ticket_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '票种',
  `channel` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '渠道类型',
  `date` date NOT NULL COMMENT '库存日期',
  `freeze_num` int(10) NOT NULL COMMENT '冻结数量',
  `freeze_time` int(10) UNSIGNED NOT NULL COMMENT '冻结时间',
  `expire_time` int(10) UNSIGNED NOT NULL COMMENT '过期时间',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态（1:冻结中 2:已使用 3:已释放）',
  `release_time` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '释放时间',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_order_id`(`order_id`) USING BTREE,
  INDEX `idx_stock_id`(`stock_id`) USING BTREE,
  INDEX `idx_expire_status`(`expire_time`, `status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '库存冻结日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_stock_log
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_stock_log`;
CREATE TABLE `fa_fzly_stock_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `stock_id` int(10) NOT NULL COMMENT '库存ID',
  `product_id` int(10) NOT NULL COMMENT '产品ID',
  `product_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '产品名称',
  `channel` enum('offline','ota','mini_program') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '渠道',
  `change_type` enum('increase','decrease') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '变动类型:increase=增加,decrease=减少',
  `change_num` int(10) NOT NULL COMMENT '变动数量',
  `before_stock` int(10) NOT NULL COMMENT '变动前库存',
  `after_stock` int(10) NOT NULL COMMENT '变动后库存',
  `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '变动原因',
  `operate_user` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作人',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_stock_id`(`stock_id`) USING BTREE,
  INDEX `idx_createtime`(`createtime`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '库存变动记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_stock_old
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_stock_old`;
CREATE TABLE `fa_fzly_stock_old`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `product_id` int(10) UNSIGNED NOT NULL COMMENT '商品ID',
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '当前库存数量',
  `locked_quantity` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '锁定库存数量',
  `warn_quantity` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '库存预警数量',
  `createtime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updatetime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `product_id`(`product_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '库存表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_stock_report
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_stock_report`;
CREATE TABLE `fa_fzly_stock_report`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '报表标题',
  `type` enum('daily','weekly','monthly','yearly') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '报表类型',
  `fields` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '报表字段(JSON)',
  `channels` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '渠道(JSON)',
  `product_types` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '产品类型(JSON)',
  `is_timed` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否定时发送:0=否,1=是',
  `timed_time` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '定时时间',
  `receivers` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '接收邮箱(JSON)',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态:0=禁用,1=启用',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '库存报表配置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_stock_time_slot
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_stock_time_slot`;
CREATE TABLE `fa_fzly_stock_time_slot`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `stock_id` int(10) UNSIGNED NOT NULL COMMENT '关联库存ID',
  `ticket_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '票种',
  `channel` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '渠道类型',
  `date` date NOT NULL COMMENT '库存日期',
  `time_slot` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '时间段（如：上午/下午/全天）',
  `start_time` time NOT NULL COMMENT '开始时间',
  `end_time` time NOT NULL COMMENT '结束时间',
  `total_stock` int(10) NOT NULL DEFAULT 0 COMMENT '时段总库存',
  `used_stock` int(10) NOT NULL DEFAULT 0 COMMENT '时段已使用库存',
  `available_stock` int(10) NOT NULL DEFAULT 0 COMMENT '时段可用库存',
  `lock_stock` int(10) NOT NULL DEFAULT 0 COMMENT '时段锁定库存',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uniq_stock_time_slot`(`stock_id`, `time_slot`) USING BTREE,
  INDEX `idx_date_time_slot`(`date`, `time_slot`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '分时库存表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_stock_valuation
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_stock_valuation`;
CREATE TABLE `fa_fzly_stock_valuation`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `valuation_date` date NOT NULL COMMENT '评估日期',
  `product_id` int(10) NOT NULL COMMENT '产品ID',
  `product_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '产品名称',
  `stock_quantity` int(10) NOT NULL DEFAULT 0 COMMENT '库存数量',
  `cost_price` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '成本单价',
  `total_value` decimal(12, 2) NOT NULL DEFAULT 0.00 COMMENT '库存总价值',
  `valuation_status` enum('normal','warning','expired') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'normal' COMMENT '评估状态',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '备注',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_valuation_date`(`valuation_date`) USING BTREE,
  INDEX `idx_product_id`(`product_id`) USING BTREE,
  INDEX `idx_status`(`valuation_status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '库存价值评估表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_stock_visual
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_stock_visual`;
CREATE TABLE `fa_fzly_stock_visual`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '可视化标题',
  `type` enum('line','bar','pie','map') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '图表类型',
  `data_source` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '数据来源',
  `params` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '参数配置(JSON)',
  `weigh` int(10) NULL DEFAULT 0 COMMENT '排序权重',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态:0=禁用,1=启用',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '库存可视化配置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_stock_warn
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_stock_warn`;
CREATE TABLE `fa_fzly_stock_warn`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `product_id` int(10) NOT NULL COMMENT '产品ID',
  `product_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '产品类型',
  `channel` enum('offline','ota','mini_program') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '渠道',
  `warn_value` int(10) NOT NULL COMMENT '预警值',
  `receivers` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '接收人(手机号数组JSON)',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态:0=禁用,1=启用',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_product_channel`(`product_id`, `channel`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '库存预警设置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_supplier
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_supplier`;
CREATE TABLE `fa_fzly_supplier`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '供应商名称',
  `contact_person` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '联系人',
  `contact_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '联系电话',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '地址',
  `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '备注',
  `status` enum('normal','hidden') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'normal' COMMENT '状态',
  `createtime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updatetime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '供应商表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_system_maintenance
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_system_maintenance`;
CREATE TABLE `fa_fzly_system_maintenance`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '维护标题',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '维护内容',
  `maintenance_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '维护类型',
  `start_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '开始时间',
  `end_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '结束时间',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态：0-未开始，1-进行中，2-已完成',
  `operator_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '操作人ID',
  `operator_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '操作人姓名',
  `createtime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updatetime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_status`(`status`) USING BTREE,
  INDEX `idx_time`(`start_time`, `end_time`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统维护记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_system_operation_log
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_system_operation_log`;
CREATE TABLE `fa_fzly_system_operation_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员ID',
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '管理员用户名',
  `operation_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作类型',
  `operation_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '操作内容',
  `module` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '操作模块',
  `controller` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '控制器',
  `action` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '方法',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '操作URL',
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '操作IP',
  `useragent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT 'User-Agent',
  `createtime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '操作时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_admin_id`(`admin_id`) USING BTREE,
  INDEX `idx_createtime`(`createtime`) USING BTREE,
  INDEX `idx_operation_type`(`operation_type`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统操作日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_trends
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_trends`;
CREATE TABLE `fa_fzly_trends`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type_id` int(10) NULL DEFAULT 0 COMMENT '动态分类',
  `user_id` int(10) NULL DEFAULT 0 COMMENT '发布用户',
  `title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '标题',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '内容',
  `images` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '详情图',
  `dz_nums` int(10) NULL DEFAULT 0 COMMENT '点赞数',
  `common_nums` int(10) NULL DEFAULT 0 COMMENT '评论数',
  `status` enum('1','2','3','4','5') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '状态:1=草稿,2=未审核,3=审核通过,4=审核拒绝,5=已下架',
  `weigh` int(10) NULL DEFAULT 0 COMMENT '权重',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '拒绝原因',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '动态表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_trends_type
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_trends_type`;
CREATE TABLE `fa_fzly_trends_type`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '标题',
  `weigh` int(10) NULL DEFAULT 0 COMMENT '权重',
  `status` enum('normal','hidden') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'normal' COMMENT '状态',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '动态分类表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_user_admission
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_user_admission`;
CREATE TABLE `fa_fzly_user_admission`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) NULL DEFAULT NULL COMMENT '用户id',
  `admission_id` int(10) NULL DEFAULT NULL COMMENT '景区id',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '景区管理账号表' ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for fa_fzly_user_bank
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_user_bank`;
CREATE TABLE `fa_fzly_user_bank`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) NULL DEFAULT 0 COMMENT '用户ID',
  `type` enum('1','2','3') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '提现类型 1=银行卡 2=微信 3=支付宝',
  `bank_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '银行名称',
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '姓名',
  `bank_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '银行卡号',
  `bank_mobile` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '手机号',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  `zfb_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '支付宝账号',
  `wx_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '微信账号',
  `status` enum('0','1','2') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '状态:0=审核中,1=审核通过,2=审核拒绝',
  `refuse` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '审核拒绝原因',
  `bank_icon` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '图标',
  `bank_type` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '银行卡类型',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '提现账户表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_user_detail
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_user_detail`;
CREATE TABLE `fa_fzly_user_detail`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) NULL DEFAULT NULL COMMENT '用户id',
  `openid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'openid',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  `back_avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '背景图',
  `constellation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '星座',
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '标签',
  `real_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '姓名',
  `like_s` int(10) NOT NULL DEFAULT 0 COMMENT '获赞数',
  `gz_s` int(10) NOT NULL DEFAULT 0 COMMENT '被关注数',
  `fs_s` int(10) NOT NULL DEFAULT 0 COMMENT '粉丝数',
  `view_s` int(10) NOT NULL DEFAULT 0 COMMENT '被浏览数',
  `is_dy` int(10) NOT NULL DEFAULT 0 COMMENT '是否导游',
  `c_city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '常住地',
  `month_servers` int(10) NOT NULL DEFAULT 50 COMMENT '月服务数量(虚拟)',
  `parent_id` int(10) NULL DEFAULT NULL COMMENT '上级用户id',
  `yqtime` bigint(16) NULL DEFAULT NULL COMMENT '分销邀请时间',
  `proxy_amound` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '佣金',
  `freeze_amound` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '冻结佣金',
  `proxy_level` int(10) NULL DEFAULT 1 COMMENT '代理等级 默认1 青铜',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户详情表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_user_dz
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_user_dz`;
CREATE TABLE `fa_fzly_user_dz`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) NULL DEFAULT NULL COMMENT '用户id',
  `con_id` int(10) NULL DEFAULT NULL COMMENT '内容id',
  `type` enum('1','2','3','4') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '1=攻略,2=游记,3=美食,4=动态',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户点赞表' ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for fa_fzly_user_follow
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_user_follow`;
CREATE TABLE `fa_fzly_user_follow`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) NULL DEFAULT NULL COMMENT '用户id',
  `df_id` int(10) NULL DEFAULT NULL COMMENT '对方id',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户关注表' ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for fa_fzly_user_guide
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_user_guide`;
CREATE TABLE `fa_fzly_user_guide`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) NULL DEFAULT NULL COMMENT '用户id',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '姓名',
  `tel` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '手机号',
  `number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '导游资格证号',
  `organ` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '发证机关',
  `bank` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '银行卡',
  `font_image` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '身份证正面',
  `back_image` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '身份证反面',
  `certificate_image` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '导游证',
  `status` enum('1','2','3','4') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '状态:1=未审核,2=审核通过,3=审核拒绝,4=下架',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '导游信息审核表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_user_sc
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_user_sc`;
CREATE TABLE `fa_fzly_user_sc`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) NULL DEFAULT NULL COMMENT '用户id',
  `con_id` int(10) NULL DEFAULT NULL COMMENT '内容id',
  `type` enum('1','2','3','4') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '1=攻略,2=游记,3=美食,4=导游',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 24 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户收藏表' ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for fa_fzly_user_travel
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_user_travel`;
CREATE TABLE `fa_fzly_user_travel`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) NULL DEFAULT NULL COMMENT '用户id',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '姓名',
  `tel` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '电话',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  `is_default` int(10) NOT NULL DEFAULT 0 COMMENT '是否默认',
  `id_card` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '身份证号',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '出行人表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_visitor_report
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_visitor_report`;
CREATE TABLE `fa_fzly_visitor_report`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `date` datetime NOT NULL COMMENT '日期时间',
  `entry_num` int(10) NOT NULL DEFAULT 0 COMMENT '入园人数',
  `exit_num` int(10) NOT NULL DEFAULT 0 COMMENT '出园人数',
  `current_num` int(10) NOT NULL DEFAULT 0 COMMENT '当前在场人数',
  `staff_entry` int(10) NOT NULL DEFAULT 0 COMMENT '工作人员入场',
  `onsite_ticket` int(10) NOT NULL DEFAULT 0 COMMENT '现场购票入场',
  `ota_ticket` int(10) NOT NULL DEFAULT 0 COMMENT 'OTA购票入场',
  `official_ticket` int(10) NOT NULL DEFAULT 0 COMMENT '官方购票入场',
  `source_area` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '客源地分布(JSON)',
  `max_capacity` int(10) NOT NULL COMMENT '单日最大承载量',
  `instant_max_capacity` int(10) NOT NULL COMMENT '瞬时最大承载量',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_date`(`date`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 40004 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '客流报表数据表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_fzly_withdraw_log
-- ----------------------------
DROP TABLE IF EXISTS `fa_fzly_withdraw_log`;
CREATE TABLE `fa_fzly_withdraw_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) NULL DEFAULT 0 COMMENT '导游',
  `withdraw_money` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '提现金额',
  `leave_withdraw_money` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '剩余可提现金额',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '提现时间',
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '提现方式',
  `status` enum('1','2','3','4') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '状态:1=待审核,2=审核拒绝,3=待到账,4=已到账',
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '审核拒绝原因',
  `draw_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '提现订单号',
  `batch_id` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '第三方回调单号',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '提现记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_sms
-- ----------------------------
DROP TABLE IF EXISTS `fa_sms`;
CREATE TABLE `fa_sms`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `event` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '事件',
  `mobile` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '手机号',
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '验证码',
  `times` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '验证次数',
  `ip` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT 'IP',
  `createtime` bigint(16) UNSIGNED NULL DEFAULT 0 COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '短信验证码表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_stock
-- ----------------------------
DROP TABLE IF EXISTS `fa_stock`;
CREATE TABLE `fa_stock`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `ticket_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '票种',
  `channel` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '渠道类型（官网/OTA/窗口）',
  `total_stock` int(10) NOT NULL DEFAULT 0 COMMENT '总库存',
  `used_stock` int(10) NOT NULL DEFAULT 0 COMMENT '已使用库存',
  `available_stock` int(10) NOT NULL DEFAULT 0 COMMENT '可用库存',
  `lock_stock` int(10) NOT NULL DEFAULT 0 COMMENT '锁定库存',
  `date` date NOT NULL COMMENT '库存日期',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态（1启用/0禁用）',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uniq_ticket_channel_date`(`ticket_type`, `channel`, `date`) USING BTREE,
  INDEX `idx_available_stock`(`available_stock`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '库存信息表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_stock_adjust_log
-- ----------------------------
DROP TABLE IF EXISTS `fa_stock_adjust_log`;
CREATE TABLE `fa_stock_adjust_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `stock_id` int(10) UNSIGNED NOT NULL COMMENT '库存ID',
  `ticket_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '票种',
  `channel` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '渠道类型',
  `date` date NOT NULL COMMENT '库存日期',
  `time_slot` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '涉及时间段',
  `adjust_type` tinyint(2) NOT NULL COMMENT '调整类型（1:增加 2:减少 3:冻结 4:释放 5:初始设置）',
  `adjust_value` int(10) NOT NULL COMMENT '调整数量',
  `before_stock` int(10) NOT NULL COMMENT '调整前库存',
  `after_stock` int(10) NOT NULL COMMENT '调整后库存',
  `operator_id` int(10) UNSIGNED NOT NULL COMMENT '操作人ID',
  `operator_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作人姓名',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '调整备注',
  `order_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '关联订单号',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_stock_id`(`stock_id`) USING BTREE,
  INDEX `idx_create_time`(`create_time`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '库存调整记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_stock_warn_log
-- ----------------------------
DROP TABLE IF EXISTS `fa_stock_warn_log`;
CREATE TABLE `fa_stock_warn_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `stock_id` int(10) UNSIGNED NOT NULL COMMENT '库存ID',
  `ticket_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '票种',
  `channel` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '渠道类型',
  `date` date NOT NULL COMMENT '库存日期',
  `current_stock` int(10) NOT NULL COMMENT '当前库存',
  `warn_value` int(10) NOT NULL COMMENT '预警阈值',
  `warn_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '预警时间',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '处理状态（0未处理/1已处理）',
  `process_time` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '处理时间',
  `process_user_id` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '处理人ID',
  `process_user_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '处理人姓名',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_stock_id`(`stock_id`) USING BTREE,
  INDEX `idx_status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '库存预警记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_system_setting
-- ----------------------------
DROP TABLE IF EXISTS `fa_system_setting`;
CREATE TABLE `fa_system_setting`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '配置键名（唯一）',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '配置名称',
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '配置值',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '配置类型：1-基础配置，2-硬件配置，3-支付配置',
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `key`(`key`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统设置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_system_setting_basic
-- ----------------------------
DROP TABLE IF EXISTS `fa_system_setting_basic`;
CREATE TABLE `fa_system_setting_basic`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `scenic_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '景区名称',
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT 'LOGO路径',
  `contact_phone` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '联系电话',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '详细地址',
  `system_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '景区票务系统' COMMENT '系统名称',
  `copyright` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '© 2024 景区票务系统 版权所有' COMMENT '版权信息',
  `icp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT 'ICP备案号',
  `refresh_interval` int(3) NOT NULL DEFAULT 5 COMMENT '数据刷新间隔(秒)',
  `login_captcha` tinyint(1) NOT NULL DEFAULT 1 COMMENT '登录验证码(1启用/0禁用)',
  `operation_log` tinyint(1) NOT NULL DEFAULT 1 COMMENT '操作日志记录(1启用/0禁用)',
  `default_lang` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'zh-cn' COMMENT '默认语言',
  `time_format` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Y-m-d H:i:s' COMMENT '时间显示格式',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uniq_scenic_name`(`scenic_name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统基础信息配置' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_system_setting_order_rule
-- ----------------------------
DROP TABLE IF EXISTS `fa_system_setting_order_rule`;
CREATE TABLE `fa_system_setting_order_rule`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `pay_timeout` int(3) NOT NULL DEFAULT 30 COMMENT '支付超时时间（分钟）',
  `stock_release_interval` int(2) NOT NULL DEFAULT 5 COMMENT '库存释放间隔（分钟）',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态（1启用/0禁用）',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '订单规则配置' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_system_setting_price_rule
-- ----------------------------
DROP TABLE IF EXISTS `fa_system_setting_price_rule`;
CREATE TABLE `fa_system_setting_price_rule`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `holiday_premium_switch` tinyint(1) NOT NULL DEFAULT 0 COMMENT '节假日溢价开关（1启用/0禁用）',
  `holiday_premium_rate` int(3) NOT NULL DEFAULT 100 COMMENT '溢价比例（100%-200%）',
  `group_discount_base` int(2) NOT NULL DEFAULT 90 COMMENT '团体票折扣基准（50%-99%）',
  `apply_ticket_types` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '适用票种（数组JSON）',
  `holiday_dates` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '绑定节假日日期（数组JSON）',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态（1启用/0禁用）',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '票价规则配置' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_system_setting_stock_warn
-- ----------------------------
DROP TABLE IF EXISTS `fa_system_setting_stock_warn`;
CREATE TABLE `fa_system_setting_stock_warn`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `channel` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '渠道类型（官网/OTA/窗口）',
  `date_start` date NOT NULL COMMENT '生效开始日期',
  `date_end` date NOT NULL COMMENT '生效结束日期',
  `ticket_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '票种',
  `warn_value` int(5) NOT NULL COMMENT '预警阈值',
  `warn_type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '预警类型（1:固定值 2:百分比）',
  `warn_percent` decimal(5, 2) NULL DEFAULT 0.00 COMMENT '预警百分比（0-100）',
  `receivers` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '通知接收人（手机号数组JSON）',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uniq_channel_date_ticket`(`channel`, `date_start`, `date_end`, `ticket_type`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '库存预警阈值配置' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_test
-- ----------------------------
DROP TABLE IF EXISTS `fa_test`;
CREATE TABLE `fa_test`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) NULL DEFAULT 0 COMMENT '会员ID',
  `admin_id` int(10) NULL DEFAULT 0 COMMENT '管理员ID',
  `category_id` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '分类ID(单选)',
  `category_ids` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '分类ID(多选)',
  `tags` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '标签',
  `week` enum('monday','tuesday','wednesday') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '星期(单选):monday=星期一,tuesday=星期二,wednesday=星期三',
  `flag` set('hot','index','recommend') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '标志(多选):hot=热门,index=首页,recommend=推荐',
  `genderdata` enum('male','female') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'male' COMMENT '性别(单选):male=男,female=女',
  `hobbydata` set('music','reading','swimming') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '爱好(多选):music=音乐,reading=读书,swimming=游泳',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '标题',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '内容',
  `image` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '图片',
  `images` varchar(1500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '图片组',
  `attachfile` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '附件',
  `keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '关键字',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '描述',
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '省市',
  `array` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '数组:value=值',
  `json` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '配置:key=名称,value=值',
  `multiplejson` varchar(1500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '二维数组:title=标题,intro=介绍,author=作者,age=年龄',
  `price` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '价格',
  `views` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '点击',
  `workrange` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '时间区间',
  `startdate` date NULL DEFAULT NULL COMMENT '开始日期',
  `activitytime` datetime NULL DEFAULT NULL COMMENT '活动时间(datetime)',
  `year` year NULL DEFAULT NULL COMMENT '年',
  `times` time NULL DEFAULT NULL COMMENT '时间',
  `refreshtime` bigint(16) NULL DEFAULT NULL COMMENT '刷新时间',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  `deletetime` bigint(16) NULL DEFAULT NULL COMMENT '删除时间',
  `weigh` int(10) NULL DEFAULT 0 COMMENT '权重',
  `switch` tinyint(1) NULL DEFAULT 0 COMMENT '开关',
  `status` enum('normal','hidden') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'normal' COMMENT '状态',
  `state` enum('0','1','2') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '状态值:0=禁用,1=正常,2=推荐',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '测试表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_user
-- ----------------------------
DROP TABLE IF EXISTS `fa_user`;
CREATE TABLE `fa_user`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '组别ID',
  `username` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '昵称',
  `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '密码',
  `salt` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '密码盐',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '电子邮箱',
  `mobile` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '手机号',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '头像',
  `level` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '等级',
  `gender` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '性别',
  `birthday` date NULL DEFAULT NULL COMMENT '生日',
  `bio` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '格言',
  `money` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '余额',
  `score` int(10) NOT NULL DEFAULT 0 COMMENT '积分',
  `successions` int(10) UNSIGNED NOT NULL DEFAULT 1 COMMENT '连续登录天数',
  `maxsuccessions` int(10) UNSIGNED NOT NULL DEFAULT 1 COMMENT '最大连续登录天数',
  `prevtime` bigint(16) NULL DEFAULT NULL COMMENT '上次登录时间',
  `logintime` bigint(16) NULL DEFAULT NULL COMMENT '登录时间',
  `loginip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '登录IP',
  `loginfailure` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '失败次数',
  `loginfailuretime` bigint(16) NULL DEFAULT NULL COMMENT '最后登录失败时间',
  `joinip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '加入IP',
  `jointime` bigint(16) NULL DEFAULT NULL COMMENT '加入时间',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  `token` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT 'Token',
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '状态',
  `verification` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '验证',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `username`(`username`) USING BTREE,
  INDEX `email`(`email`) USING BTREE,
  INDEX `mobile`(`mobile`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '会员表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_user_group
-- ----------------------------
DROP TABLE IF EXISTS `fa_user_group`;
CREATE TABLE `fa_user_group`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '组名',
  `rules` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '权限节点',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '添加时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  `status` enum('normal','hidden') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '会员组表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_user_money_log
-- ----------------------------
DROP TABLE IF EXISTS `fa_user_money_log`;
CREATE TABLE `fa_user_money_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员ID',
  `money` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '变更余额',
  `before` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '变更前余额',
  `after` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '变更后余额',
  `memo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '备注',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '会员余额变动表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_user_rule
-- ----------------------------
DROP TABLE IF EXISTS `fa_user_rule`;
CREATE TABLE `fa_user_rule`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid` int(10) NULL DEFAULT NULL COMMENT '父ID',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '名称',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '标题',
  `remark` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '备注',
  `ismenu` tinyint(1) NULL DEFAULT NULL COMMENT '是否菜单',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) NULL DEFAULT 0 COMMENT '权重',
  `status` enum('normal','hidden') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '会员规则表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_user_score_log
-- ----------------------------
DROP TABLE IF EXISTS `fa_user_score_log`;
CREATE TABLE `fa_user_score_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员ID',
  `score` int(10) NOT NULL DEFAULT 0 COMMENT '变更积分',
  `before` int(10) NOT NULL DEFAULT 0 COMMENT '变更前积分',
  `after` int(10) NOT NULL DEFAULT 0 COMMENT '变更后积分',
  `memo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '备注',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '会员积分变动表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_user_token
-- ----------------------------
DROP TABLE IF EXISTS `fa_user_token`;
CREATE TABLE `fa_user_token`  (
  `token` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Token',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员ID',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `expiretime` bigint(16) NULL DEFAULT NULL COMMENT '过期时间',
  PRIMARY KEY (`token`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '会员Token表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fa_version
-- ----------------------------
DROP TABLE IF EXISTS `fa_version`;
CREATE TABLE `fa_version`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `oldversion` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '旧版本号',
  `newversion` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '新版本号',
  `packagesize` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '包大小',
  `content` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '升级内容',
  `downloadurl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '下载地址',
  `enforce` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '强制更新',
  `createtime` bigint(16) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) NULL DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) NOT NULL DEFAULT 0 COMMENT '权重',
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '版本表' ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
