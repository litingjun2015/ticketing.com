CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_admission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type_ids` varchar(20) NOT NULL DEFAULT '0' COMMENT '类型',
  `title` varchar(200) DEFAULT NULL COMMENT '门票名称',
  `flag` set('hot','index','recommend') DEFAULT '' COMMENT '标志:hot=热门,recommend=推荐',
  `city` varchar(100) DEFAULT NULL COMMENT '省市',
  `image` varchar(200) NOT NULL DEFAULT '' COMMENT '缩略图',
  `images` varchar(255) NOT NULL DEFAULT '' COMMENT '详情图',
  `tags` varchar(255) DEFAULT NULL COMMENT '标签',
  `address` varchar(255) DEFAULT NULL COMMENT '地址',
  `address_xx` varchar(255) DEFAULT NULL COMMENT '详细地址',
  `lon` varchar(100) DEFAULT NULL COMMENT '经度',
  `lat` varchar(100) DEFAULT NULL COMMENT '纬度',
  `content` text COMMENT '简介',
  `yd_multiplejson` varchar(1500) DEFAULT NULL COMMENT '预定:icon=图标,intro=介绍',
  `guarantee` varchar(200) DEFAULT NULL COMMENT '保障',
  `open_times` time DEFAULT NULL COMMENT '开启时间',
  `end_times` time DEFAULT NULL COMMENT '关闭时间',
  `mp_multiplejson` varchar(1500) DEFAULT NULL COMMENT '门票:intro=名称,label=标签,text=简述,score=来源,price=价格,line_price=价格,ys=已售',
  `score` varchar(200) DEFAULT NULL COMMENT '评分',
  `common_nums` varchar(200) DEFAULT NULL COMMENT '评论数',
  `weigh` int(10) DEFAULT '0' COMMENT '权重',
  `status` enum('normal','hidden') DEFAULT 'normal' COMMENT '状态',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `deletetime` bigint(16) DEFAULT NULL COMMENT '删除时间',
  `lowest_price` decimal(10,2) DEFAULT '0.00' COMMENT '最低价格',
  `highest_price` decimal(10,2) DEFAULT '0.00' COMMENT '最高价格',
  `type_id` int(10) DEFAULT '0' COMMENT '门票分类',
  `hot` int(10) DEFAULT '0' COMMENT '热度',
  `desc` varchar(100) DEFAULT NULL COMMENT '简述',
  `view_nums` int(10) DEFAULT '0' COMMENT '浏览数',
  `attractions_id` int(10) DEFAULT '0' COMMENT '所属景区',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COMMENT='门票表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_admission_money_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attractions_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '景区id',
  `admission_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门票id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '支付用户id',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更余额',
  `before` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更前余额',
  `after` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更后余额',
  `y` varchar(100) DEFAULT NULL COMMENT '年',
  `m` varchar(100) DEFAULT NULL COMMENT '月',
  `d` varchar(100) DEFAULT NULL COMMENT '日',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='景区收益表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_admission_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(100) DEFAULT NULL COMMENT '名称',
  `weigh` int(10) DEFAULT '0' COMMENT '权重',
  `status` enum('normal','hidden') DEFAULT 'normal' COMMENT '状态',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='门票分类表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_advertising` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `url_id` int(10) DEFAULT '0' COMMENT '页面地址ID',
  `image` varchar(200) DEFAULT NULL COMMENT '图片',
  `tz_url` varchar(100) DEFAULT NULL COMMENT '链接',
  `enddate` date DEFAULT NULL COMMENT '过期时间',
  `status` enum('normal','hidden') DEFAULT 'normal' COMMENT '状态',
  `position` varchar(100) DEFAULT NULL COMMENT '图片位置',
  `params` varchar(100) DEFAULT NULL COMMENT '参数',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `url_id` (`url_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='广告位表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_advertising_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) DEFAULT '0' COMMENT '用户id',
  `advertising_id` int(10) DEFAULT '0' COMMENT '广告位id',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `counts` int(10) DEFAULT '0' COMMENT '点击次数',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='广告位日志表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_app` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(100) DEFAULT NULL COMMENT '分类名称',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='页面表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_app_navigation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  `url` varchar(100) DEFAULT NULL COMMENT '跳转页面',
  `image` varchar(100) DEFAULT NULL COMMENT '图标',
  `params` varchar(100) DEFAULT NULL COMMENT '跳转参数',
  `weigh` int(10) DEFAULT '0' COMMENT '权重',
  `status` enum('normal','hidden') DEFAULT 'normal' COMMENT '状态',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `title` (`title`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COMMENT='首页导航栏表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_app_url` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type_id` int(10) unsigned DEFAULT '0' COMMENT '页面类型',
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  `url` varchar(100) DEFAULT NULL COMMENT 'url',
  `params` varchar(100) DEFAULT NULL COMMENT '跳转参数',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `type_id` (`type_id`) USING BTREE,
  KEY `title` (`title`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COMMENT='页面url表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_attractions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(100) DEFAULT NULL COMMENT '名称',
  `type_id` int(10) DEFAULT '0' COMMENT '分类',
  `hot` int(10) DEFAULT '0' COMMENT '热度',
  `status` enum('1','2','0') DEFAULT '0' COMMENT '审核状态:0=未审核,1=已审核,2=审核失败',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `image` varchar(200) NOT NULL DEFAULT '' COMMENT '缩略图',
  `city` varchar(100) DEFAULT NULL COMMENT '省市',
  `desc` varchar(100) DEFAULT NULL COMMENT '简述',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '收益',
  `name` varchar(100) DEFAULT NULL COMMENT '姓名',
  `tel` varchar(100) DEFAULT NULL COMMENT '手机号',
  `card` varchar(100) DEFAULT NULL COMMENT '身份证号',
  `yy_image` varchar(100) DEFAULT NULL COMMENT '营业执照',
  `refuse` varchar(100) DEFAULT NULL COMMENT '审核拒绝原因',
  `user_id` int(10) DEFAULT '0' COMMENT '用户',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='景点表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_attractions_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  `weigh` int(10) DEFAULT '0' COMMENT '权重',
  `status` enum('normal','hidden') DEFAULT 'normal' COMMENT '状态',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='景点分类表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) DEFAULT '0' COMMENT '会员ID',
  `type` int(10) DEFAULT '0' COMMENT '类型:1=动态,2=攻略/游记/美食,3=门票',
  `con_id` int(10) DEFAULT '0' COMMENT '内容ID',
  `p_id` int(10) DEFAULT '0' COMMENT '父ID',
  `content` varchar(255) DEFAULT NULL COMMENT '评论内容',
  `u_id` int(10) DEFAULT '0' COMMENT '回复人id',
  `is_del` tinyint(1) DEFAULT '0' COMMENT '是否删除',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='评论表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `jqt_id` int(10) DEFAULT '0' COMMENT '景区类型',
  `type` enum('1','2','3') DEFAULT '1' COMMENT '状态:1=攻略,2=游记,3=美食',
  `user_id` int(10) DEFAULT '0' COMMENT '发布用户',
  `city` varchar(100) DEFAULT NULL COMMENT '发布地址',
  `title` varchar(200) NOT NULL COMMENT '标题',
  `image` varchar(100) NOT NULL COMMENT '缩略图',
  `images` varchar(255) NOT NULL COMMENT '详情图',
  `content` text NOT NULL COMMENT '内容',
  `view_nums` int(10) DEFAULT '0' COMMENT '浏览数',
  `dz_nums` int(10) DEFAULT '0' COMMENT '点赞数',
  `sc_nums` int(10) DEFAULT '0' COMMENT '收藏数',
  `common_nums` int(10) DEFAULT '0' COMMENT '评论数',
  `flag` set('hot','index','recommend') DEFAULT '' COMMENT '标志:hot=热门,recommend=推荐',
  `status` enum('1','2','3','4','5') DEFAULT '1' COMMENT '状态:1=草稿,2=未审核,3=审核通过,4=审核拒绝,5=已下架',
  `weigh` int(10) DEFAULT '0' COMMENT '权重',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `mp_id` int(10) DEFAULT '0' COMMENT '门票',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COMMENT='攻略/游记/美食表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_coupon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(100) NOT NULL COMMENT '标题',
  `image` varchar(100) DEFAULT NULL COMMENT '图片',
  `with_amount` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '所需金额条件',
  `used_amount` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '抵扣金额',
  `quota` int(10) DEFAULT '0' COMMENT '发券数量',
  `start_time` bigint(16) DEFAULT NULL COMMENT '生效时间',
  `end_time` bigint(16) DEFAULT NULL COMMENT '失效时间',
  `draw_range` varchar(255) DEFAULT NULL COMMENT '可领取时间段',
  `remarks` varchar(255) DEFAULT NULL COMMENT '说明',
  `weigh` int(10) DEFAULT '0' COMMENT '权重',
  `status` enum('normal','hidden') DEFAULT 'normal' COMMENT '状态',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `deletetime` bigint(16) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='优惠券信息表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_coupon_receive` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) DEFAULT '0' COMMENT '会员ID',
  `coupon_id` int(10) unsigned DEFAULT '0' COMMENT '优惠券ID',
  `money` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '优惠券金额',
  `create_time` bigint(16) DEFAULT NULL COMMENT '领取时间',
  `state` enum('0','1','-1') DEFAULT '0' COMMENT '状态值:1为已使用，0为已领取未使用，-1为已过期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='优惠券领取记录表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_distribution_grade` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(100) DEFAULT NULL COMMENT '等级名称',
  `image` varchar(100) DEFAULT NULL COMMENT '等级icon',
  `one_level` int(10) unsigned DEFAULT '0' COMMENT '一级分销比例',
  `two_level` int(10) unsigned DEFAULT '0' COMMENT '二级分销比例',
  `team_xx` int(10) DEFAULT '0' COMMENT '升级条件-团队所需人数',
  `amount` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '升级条件-下级支付金额',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `deletetime` bigint(16) DEFAULT NULL COMMENT '删除时间',
  `weigh` int(10) DEFAULT '0' COMMENT '权重',
  `state` enum('0','1') DEFAULT '1' COMMENT '状态值:0=禁用,1=正常',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='分销等级表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_distribution_grade_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) DEFAULT '0' COMMENT '会员ID',
  `before` int(10) DEFAULT '0' COMMENT '变更前等级',
  `after` int(10) DEFAULT '0' COMMENT '变更后等级',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `flag` varchar(100) DEFAULT NULL COMMENT '操作人 系统,/人工',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='分销等级变更记录表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_distribution_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) DEFAULT '0' COMMENT '受益人',
  `pay_user_id` int(10) DEFAULT '0' COMMENT '支付者',
  `order_id` int(10) DEFAULT '0' COMMENT '订单ID',
  `pay_amount` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '支付金额',
  `pay_time` bigint(16) DEFAULT NULL COMMENT '支付时间',
  `distribution_amount` decimal(10,2) DEFAULT '0.00' COMMENT '分佣金额',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `status` enum('1','2') DEFAULT '1' COMMENT '状态1=待入账2=已入账',
  `lesson_id` int(10) DEFAULT '0' COMMENT '课程id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='分销明细表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_distribution_share` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) DEFAULT '0' COMMENT '会员ID',
  `p_id` int(10) DEFAULT '0' COMMENT '会员ID',
  `desc` varchar(255) DEFAULT NULL COMMENT '说明',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COMMENT='分享邀请表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_distribution_tghb` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `img` varchar(200) NOT NULL COMMENT '图片地址',
  `createtime` bigint(16) NOT NULL COMMENT '创建时间',
  `imgx` int(11) NOT NULL COMMENT '图片x轴',
  `imgy` int(11) NOT NULL COMMENT '图片y轴',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='推广海报图片';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_distribution_withdraw_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) DEFAULT '0' COMMENT '用户',
  `withdraw_money` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '提现金额',
  `leave_withdraw_money` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '剩余可提现金额',
  `createtime` bigint(16) DEFAULT NULL COMMENT '提现时间',
  `type` varchar(100) DEFAULT NULL COMMENT '提现方式',
  `status` enum('1','2','3','4') DEFAULT '1' COMMENT '状态:1=待审核,2=审核拒绝,3=待到账,4=已到账',
  `desc` varchar(255) DEFAULT NULL COMMENT '审核拒绝原因',
  `draw_no` varchar(100) DEFAULT NULL COMMENT '提现订单号',
  `batch_id` varchar(200) DEFAULT NULL COMMENT '第三方回调单号',
  `bank` varchar(200) NOT NULL COMMENT '银行卡',
  `ali` varchar(200) NOT NULL COMMENT '支付宝',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='提现记录表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_evaluate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` enum('1','2','3','4','0') DEFAULT '1' COMMENT '类型:1=门票,2=导游产品',
  `xx_json` varchar(255) DEFAULT NULL COMMENT '打星标签:value=名称',
  `tag_json` varchar(255) DEFAULT NULL COMMENT '评价标签:value=名称',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='评价管理表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) DEFAULT NULL COMMENT '用户id',
  `content` text COMMENT '内容',
  `tel` varchar(50) DEFAULT '0' COMMENT '联系方式',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='意见反馈表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_guide_money_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gudie_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '导游id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '支付用户id',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更余额',
  `before` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更前余额',
  `after` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更后余额',
  `y` varchar(100) DEFAULT NULL COMMENT '年',
  `m` varchar(100) DEFAULT NULL COMMENT '月',
  `d` varchar(100) DEFAULT NULL COMMENT '日',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='导游收益表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_guide_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `image` varchar(100) NOT NULL COMMENT '缩略图',
  `user_id` int(10) DEFAULT '0' COMMENT '导游',
  `title` varchar(200) NOT NULL COMMENT '产品标题',
  `jq_title` varchar(200) NOT NULL COMMENT '景区地点',
  `zm_image` varchar(200) NOT NULL COMMENT '景区合作证明',
  `type_id` int(10) DEFAULT '0' COMMENT '产品分类',
  `model` set('1','2','3','4') DEFAULT '' COMMENT '车型:1=舒适五座,2=商务七座,3=中 大巴车,4=暂无',
  `duration` set('1','2','3','4','5') DEFAULT '' COMMENT '时长:1=八小时,2=4小时,3=2小时,4=1小时,5=暂无',
  `yd_multiplejson` varchar(1500) DEFAULT NULL COMMENT '预订:icon=图标,intro=介绍',
  `guarantee` varchar(200) DEFAULT NULL COMMENT '保障',
  `cp_content` text COMMENT '产品特色',
  `tw_content` text COMMENT '图文详情',
  `fy_content` text COMMENT '费用说明',
  `tg_content` text COMMENT '退改原则',
  `tg_multiplejson` varchar(1500) DEFAULT NULL COMMENT '退改规则:time=取消时间,fy=损失费用',
  `status` enum('1','2','3','4') DEFAULT '1' COMMENT '状态:1=未审核,2=审核通过,3=审核拒绝,4=下架',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '价格',
  `reason` varchar(255) DEFAULT NULL COMMENT '拒绝原因',
  `weigh` int(10) DEFAULT '0' COMMENT '权重',
  `view_nums` int(10) DEFAULT '0' COMMENT '浏览数',
  `sc_nums` int(10) DEFAULT '0' COMMENT '收藏数',
  `score` varchar(200) DEFAULT NULL COMMENT '评分',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COMMENT='导游产品表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_guide_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  `weigh` int(10) DEFAULT '0' COMMENT '权重',
  `status` enum('normal','hidden') DEFAULT 'normal' COMMENT '状态',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='导游产品分类表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_house` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `brand_id` int(10) NOT NULL DEFAULT '0' COMMENT '品牌id',
  `title` varchar(200) DEFAULT NULL COMMENT '酒店名称',
  `city` varchar(100) DEFAULT NULL COMMENT '省市',
  `image` varchar(200) NOT NULL DEFAULT '' COMMENT '缩略图',
  `stay_type` set('1','2','3','4','5','6','7') DEFAULT '' COMMENT '住宿类型:1=青年旅社,2=公寓,3=民宿,4=度假酒店,5=别墅,6=主题酒店,7=精选酒店',
  `grade` enum('1','2','3','4') DEFAULT '1' COMMENT '档次:1=经济,2=舒适,3=高档,4=豪华',
  `facilities` set('1','2','3','4','5') DEFAULT '' COMMENT '热门设施:1=温泉,2=厨房,3=停车场,4=洗衣房,5=wifi',
  `images` varchar(255) NOT NULL DEFAULT '' COMMENT '详情图',
  `address` varchar(255) DEFAULT NULL COMMENT '地址',
  `address_xx` varchar(255) DEFAULT NULL COMMENT '详细地址',
  `lon` varchar(100) DEFAULT NULL COMMENT '经度',
  `lat` varchar(100) DEFAULT NULL COMMENT '纬度',
  `multiplejson_yd` varchar(1500) DEFAULT NULL COMMENT '预订:icon=图标,intro=介绍',
  `guarantee` varchar(200) DEFAULT NULL COMMENT '保障',
  `multiplejson_md` varchar(1500) DEFAULT NULL COMMENT '卖点-设施:icon=图标,label=标签,intro=介绍',
  `weigh` int(10) DEFAULT '0' COMMENT '权重',
  `status` enum('normal','hidden') DEFAULT 'normal' COMMENT '状态',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `deletetime` bigint(16) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COMMENT='酒店表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_house_brand` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(100) DEFAULT NULL COMMENT '品牌名称',
  `weigh` int(10) DEFAULT '0' COMMENT '权重',
  `switch` tinyint(1) DEFAULT '1' COMMENT '开关',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='酒店品牌表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_message_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) DEFAULT '0' COMMENT '用户',
  `point` varchar(100) DEFAULT NULL COMMENT '推送消息节点',
  `status` enum('0','1') DEFAULT '1' COMMENT '状态 0=失败 1=成功',
  `result` text COMMENT '返回结果',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='消息推送日志表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_message_push` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `message_type` enum('1','2','3','4','5') DEFAULT '1' COMMENT '模版分类1=支付通知模版,2=订单核销通知模版',
  `masterplate` varchar(100) DEFAULT NULL COMMENT '模版消息ID',
  `multiplejson` varchar(1500) DEFAULT NULL COMMENT '详细内容:title=字段名,keyword=微信关键字,value=介绍',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='消息推送配置表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `order_no` varchar(100) DEFAULT NULL COMMENT '订单编号',
  `order_type` enum('1','2','3','4','0') DEFAULT '1' COMMENT '订单类型:1=门票订单,2=导游订单',
  `user_id` int(10) DEFAULT '0' COMMENT '用户',
  `goods_id` int(10) DEFAULT '0' COMMENT '商品id',
  `travel_ids` varchar(100) DEFAULT '0' COMMENT '出行人',
  `coupon_id` int(10) DEFAULT '0' COMMENT '优惠券ID',
  `status` enum('1','2','3','4','5','6') DEFAULT '1' COMMENT '订单状态:1=待付款,2=已付款,3=已核销,4=待退款,5=已取消',
  `price` decimal(10,2) DEFAULT NULL COMMENT '商品单价',
  `count` int(10) DEFAULT NULL COMMENT '购买数量',
  `order_amount_total` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '实际付款金额',
  `out_trade_no` varchar(200) DEFAULT NULL COMMENT '三方支付单号',
  `remark` varchar(200) DEFAULT NULL COMMENT '订单备注',
  `yd_dsj` varchar(200) DEFAULT NULL COMMENT '预约时长',
  `yd_time` varchar(200) DEFAULT NULL COMMENT '预订日期',
  `yd_model` varchar(200) DEFAULT NULL COMMENT '预约车型',
  `pay_time` varchar(100) DEFAULT NULL COMMENT '支付时间',
  `out_refund_no` varchar(255) DEFAULT NULL COMMENT '退款单号',
  `refund_id` varchar(255) DEFAULT NULL COMMENT '三方退款单号',
  `apply` varchar(255) DEFAULT NULL COMMENT '退款申请原因',
  `last_status` int(10) DEFAULT '0' COMMENT '退款前一步订单状态',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `guide_id` int(10) DEFAULT '0' COMMENT '导游id',
  `is_hx` int(10) DEFAULT '0' COMMENT '是否已核销',
  `attractions_id` int(10) DEFAULT '0' COMMENT '景区id(用于统计管理账号下的订单)',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='订单表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_order_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `order_id` int(100) DEFAULT NULL COMMENT '订单id',
  `travel_id` varchar(100) DEFAULT '0' COMMENT '出行人id',
  `status` enum('1','2','3','4','5','6') DEFAULT '1' COMMENT '订单状态:1=待核销,2=已核销,3=已取消',
  `code` varchar(100) DEFAULT NULL COMMENT '订单核销码',
  `code_image` varchar(100) DEFAULT NULL COMMENT '核销二维码',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='订单出行人核销表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_order_evaluate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `order_id` int(10) DEFAULT NULL COMMENT '订单id',
  `order_type` enum('1','2','3','4','0') DEFAULT '1' COMMENT '订单类型:1=门票订单,2=导游订单',
  `user_id` int(10) DEFAULT '0' COMMENT '用户',
  `guide_id` int(10) DEFAULT '0' COMMENT '导游id',
  `goods_id` int(10) DEFAULT '0' COMMENT '商品id',
  `score` varchar(100) DEFAULT NULL COMMENT '评分',
  `evaluate` varchar(255) DEFAULT NULL COMMENT '评价内容',
  `is_nm` int(10) DEFAULT '0' COMMENT '是否匿名',
  `images` varchar(255) DEFAULT NULL COMMENT '详情图',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `xx_json` text COMMENT '打星标签',
  `tag_json` text COMMENT '评价标签',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='订单评价表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_pact` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  `content` text COMMENT '内容',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) DEFAULT '0' COMMENT '权重',
  `switch` tinyint(1) DEFAULT '1' COMMENT '开关',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COMMENT='协议&政策表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `session_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会话ID',
  `sender_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发送人ID',
  `receive_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '接收人ID',
  `message_type` enum('0','1') DEFAULT '0' COMMENT '消息类型:0=富文本,1=图片',
  `message` text COMMENT '消息',
  `status` enum('0','1') NOT NULL DEFAULT '0' COMMENT '状态:0=未读,1=已读',
  `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '创建时间',
  `is_del_sender` int(10) DEFAULT '0' COMMENT '用户1是否删除',
  `is_del_receive` int(10) DEFAULT '0' COMMENT '用户2是否删除',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='聊天记录表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_revenue_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) DEFAULT '0' COMMENT '导游',
  `pay_user_id` int(10) DEFAULT '0' COMMENT '支付用户',
  `order_no` int(10) DEFAULT '0' COMMENT '订单号',
  `pay_amount` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '支付金额',
  `pay_time` bigint(16) DEFAULT NULL COMMENT '支付时间',
  `status` enum('1','2','3') DEFAULT '1' COMMENT '状态:1=待入账,2=已入账,3=已退款',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='收入明细表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_search_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) DEFAULT '0' COMMENT '会员ID',
  `title` varchar(100) DEFAULT NULL COMMENT '搜索内容',
  `number` int(10) DEFAULT NULL COMMENT '搜索次数',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='搜索历史表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_session` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户1',
  `csr_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户2',
  `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '创建时间',
  `deletetime` bigint(16) unsigned DEFAULT NULL COMMENT '删除时间',
  `is_top_user` int(10) DEFAULT '0' COMMENT '用户1是否置顶',
  `is_top_csr` int(10) DEFAULT '0' COMMENT '用户2是否置顶',
  `is_del_user` int(10) DEFAULT '0' COMMENT '用户1是否删除',
  `is_del_csr` int(10) DEFAULT '0' COMMENT '用户2是否删除',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='聊天会话表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_share` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) DEFAULT '0' COMMENT '会员ID',
  `p_id` int(10) DEFAULT '0' COMMENT '会员ID',
  `desc` varchar(255) DEFAULT NULL COMMENT '说明',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='分享邀请表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_trends` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type_id` int(10) DEFAULT '0' COMMENT '动态分类',
  `user_id` int(10) DEFAULT '0' COMMENT '发布用户',
  `title` text NOT NULL COMMENT '标题',
  `content` text COMMENT '内容',
  `images` varchar(255) DEFAULT NULL COMMENT '详情图',
  `dz_nums` int(10) DEFAULT '0' COMMENT '点赞数',
  `common_nums` int(10) DEFAULT '0' COMMENT '评论数',
  `status` enum('1','2','3','4','5') DEFAULT '1' COMMENT '状态:1=草稿,2=未审核,3=审核通过,4=审核拒绝,5=已下架',
  `weigh` int(10) DEFAULT '0' COMMENT '权重',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `reason` varchar(255) DEFAULT NULL COMMENT '拒绝原因',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COMMENT='动态表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_trends_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  `weigh` int(10) DEFAULT '0' COMMENT '权重',
  `status` enum('normal','hidden') DEFAULT 'normal' COMMENT '状态',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='动态分类表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_user_admission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) DEFAULT NULL COMMENT '用户id',
  `admission_id` int(10) DEFAULT NULL COMMENT '景区id',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='景区管理账号表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_user_bank` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) DEFAULT '0' COMMENT '用户ID',
  `type` enum('1','2','3') DEFAULT '1' COMMENT '提现类型 1=银行卡 2=微信 3=支付宝',
  `bank_name` varchar(20) DEFAULT NULL COMMENT '银行名称',
  `name` varchar(20) DEFAULT NULL COMMENT '姓名',
  `bank_code` varchar(100) DEFAULT NULL COMMENT '银行卡号',
  `bank_mobile` varchar(20) DEFAULT NULL COMMENT '手机号',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `zfb_code` varchar(100) DEFAULT NULL COMMENT '支付宝账号',
  `wx_code` varchar(100) DEFAULT NULL COMMENT '微信账号',
  `status` enum('0','1','2') DEFAULT '0' COMMENT '状态:0=审核中,1=审核通过,2=审核拒绝',
  `refuse` varchar(255) DEFAULT NULL COMMENT '审核拒绝原因',
  `bank_icon` varchar(200) DEFAULT NULL COMMENT '图标',
  `bank_type` varchar(200) DEFAULT NULL COMMENT '银行卡类型',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='提现账户表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_user_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) DEFAULT NULL COMMENT '用户id',
  `openid` varchar(100) DEFAULT NULL COMMENT 'openid',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `back_avatar` varchar(255) DEFAULT NULL COMMENT '背景图',
  `constellation` varchar(255) DEFAULT NULL COMMENT '星座',
  `label` varchar(255) DEFAULT NULL COMMENT '标签',
  `real_name` varchar(255) DEFAULT NULL COMMENT '姓名',
  `like_s` int(10) NOT NULL DEFAULT '0' COMMENT '获赞数',
  `gz_s` int(10) NOT NULL DEFAULT '0' COMMENT '被关注数',
  `fs_s` int(10) NOT NULL DEFAULT '0' COMMENT '粉丝数',
  `view_s` int(10) NOT NULL DEFAULT '0' COMMENT '被浏览数',
  `is_dy` int(10) NOT NULL DEFAULT '0' COMMENT '是否导游',
  `c_city` varchar(255) DEFAULT NULL COMMENT '常住地',
  `month_servers` int(10) NOT NULL DEFAULT '50' COMMENT '月服务数量(虚拟)',
  `parent_id` int(10) DEFAULT NULL COMMENT '上级用户id',
  `yqtime` bigint(16) DEFAULT NULL COMMENT '分销邀请时间',
  `proxy_amound` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '佣金',
  `freeze_amound` decimal(10,2) DEFAULT '0.00' COMMENT '冻结佣金',
  `proxy_level` int(10) DEFAULT '1' COMMENT '代理等级 默认1 青铜',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COMMENT='用户详情表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_user_dz` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) DEFAULT NULL COMMENT '用户id',
  `con_id` int(10) DEFAULT NULL COMMENT '内容id',
  `type` enum('1','2','3','4') DEFAULT NULL COMMENT '1=攻略,2=游记,3=美食,4=动态',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COMMENT='用户点赞表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_user_follow` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) DEFAULT NULL COMMENT '用户id',
  `df_id` int(10) DEFAULT NULL COMMENT '对方id',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='用户关注表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_user_guide` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) DEFAULT NULL COMMENT '用户id',
  `name` varchar(100) DEFAULT NULL COMMENT '姓名',
  `tel` varchar(100) DEFAULT NULL COMMENT '手机号',
  `number` varchar(100) DEFAULT NULL COMMENT '导游资格证号',
  `organ` varchar(100) DEFAULT NULL COMMENT '发证机关',
  `bank` varchar(100) DEFAULT NULL COMMENT '银行卡',
  `font_image` varchar(100) DEFAULT NULL COMMENT '身份证正面',
  `back_image` varchar(100) DEFAULT NULL COMMENT '身份证反面',
  `certificate_image` varchar(100) DEFAULT NULL COMMENT '导游证',
  `status` enum('1','2','3','4') DEFAULT '1' COMMENT '状态:1=未审核,2=审核通过,3=审核拒绝,4=下架',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COMMENT='导游信息审核表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_user_sc` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) DEFAULT NULL COMMENT '用户id',
  `con_id` int(10) DEFAULT NULL COMMENT '内容id',
  `type` enum('1','2','3','4') DEFAULT NULL COMMENT '1=攻略,2=游记,3=美食,4=导游',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COMMENT='用户收藏表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_user_travel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) DEFAULT NULL COMMENT '用户id',
  `name` varchar(100) DEFAULT NULL COMMENT '姓名',
  `tel` varchar(100) DEFAULT NULL COMMENT '电话',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `is_default` int(10) NOT NULL DEFAULT '0' COMMENT '是否默认',
  `id_card` varchar(100) DEFAULT NULL COMMENT '身份证号',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COMMENT='出行人表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_withdraw_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) DEFAULT '0' COMMENT '导游',
  `withdraw_money` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '提现金额',
  `leave_withdraw_money` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '剩余可提现金额',
  `createtime` bigint(16) DEFAULT NULL COMMENT '提现时间',
  `type` varchar(255) DEFAULT NULL COMMENT '提现方式',
  `status` enum('1','2','3','4') DEFAULT '1' COMMENT '状态:1=待审核,2=审核拒绝,3=待到账,4=已到账',
  `desc` varchar(255) DEFAULT NULL COMMENT '审核拒绝原因',
  `draw_no` varchar(100) DEFAULT NULL COMMENT '提现订单号',
  `batch_id` varchar(200) DEFAULT NULL COMMENT '第三方回调单号',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='提现记录表';

--
-- 1.2.0更新sql
--
CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_distribution_grade` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `name` varchar(100) DEFAULT NULL COMMENT '等级名称',
    `image` varchar(100) DEFAULT NULL COMMENT '等级icon',
    `one_level` int(10) unsigned DEFAULT '0' COMMENT '一级分销比例',
    `two_level` int(10) unsigned DEFAULT '0' COMMENT '二级分销比例',
    `team_xx` int(10) DEFAULT '0' COMMENT '升级条件-团队所需人数',
    `amount` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '升级条件-下级支付金额',
    `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
    `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
    `deletetime` bigint(16) DEFAULT NULL COMMENT '删除时间',
    `weigh` int(10) DEFAULT '0' COMMENT '权重',
    `state` enum('0','1') DEFAULT '1' COMMENT '状态值:0=禁用,1=正常',
    PRIMARY KEY (`id`) USING BTREE
    ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='分销等级表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_distribution_grade_log` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `user_id` int(10) DEFAULT '0' COMMENT '会员ID',
    `before` int(10) DEFAULT '0' COMMENT '变更前等级',
    `after` int(10) DEFAULT '0' COMMENT '变更后等级',
    `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
    `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
    `flag` varchar(100) DEFAULT NULL COMMENT '操作人 系统,/人工',
    PRIMARY KEY (`id`) USING BTREE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='分销等级变更记录表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_distribution_log` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `user_id` int(10) DEFAULT '0' COMMENT '受益人',
    `pay_user_id` int(10) DEFAULT '0' COMMENT '支付者',
    `order_id` int(10) DEFAULT '0' COMMENT '订单ID',
    `pay_amount` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '支付金额',
    `pay_time` bigint(16) DEFAULT NULL COMMENT '支付时间',
    `distribution_amount` decimal(10,2) DEFAULT '0.00' COMMENT '分佣金额',
    `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
    `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
    `status` enum('1','2') DEFAULT '1' COMMENT '状态1=待入账2=已入账',
    `lesson_id` int(10) DEFAULT '0' COMMENT '课程id',
    PRIMARY KEY (`id`) USING BTREE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='分销明细表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_distribution_tghb` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `img` varchar(200) NOT NULL COMMENT '图片地址',
    `createtime` bigint(16) NOT NULL COMMENT '创建时间',
    `imgx` int(11) NOT NULL COMMENT '图片x轴',
    `imgy` int(11) NOT NULL COMMENT '图片y轴',
    PRIMARY KEY (`id`) USING BTREE
    ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='推广海报图片';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_distribution_withdraw_log` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `user_id` int(10) DEFAULT '0' COMMENT '用户',
    `withdraw_money` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '提现金额',
    `leave_withdraw_money` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '剩余可提现金额',
    `createtime` bigint(16) DEFAULT NULL COMMENT '提现时间',
    `type` varchar(100) DEFAULT NULL COMMENT '提现方式',
    `status` enum('1','2','3','4') DEFAULT '1' COMMENT '状态:1=待审核,2=审核拒绝,3=待到账,4=已到账',
    `desc` varchar(255) DEFAULT NULL COMMENT '审核拒绝原因',
    `draw_no` varchar(100) DEFAULT NULL COMMENT '提现订单号',
    `batch_id` varchar(200) DEFAULT NULL COMMENT '第三方回调单号',
    PRIMARY KEY (`id`) USING BTREE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4  COMMENT='提现记录表';

CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_distribution_share` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `user_id` int(10) DEFAULT '0' COMMENT '会员ID',
    `p_id` int(10) DEFAULT '0' COMMENT '会员ID',
    `desc` varchar(255) DEFAULT NULL COMMENT '说明',
    `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
    `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
    PRIMARY KEY (`id`) USING BTREE
    ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COMMENT='分享邀请表';

ALTER TABLE `__PREFIX__fzly_user_detail` ADD COLUMN `parent_id` int(10) DEFAULT NULL COMMENT '上级用户id';
ALTER TABLE `__PREFIX__fzly_user_detail` ADD COLUMN `yqtime` bigint(16) DEFAULT NULL COMMENT '分销邀请时间';
ALTER TABLE `__PREFIX__fzly_user_detail` ADD COLUMN `proxy_amound` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '佣金';
ALTER TABLE `__PREFIX__fzly_user_detail` ADD COLUMN `freeze_amound` decimal(10,2) DEFAULT '0.00' COMMENT '冻结佣金';
ALTER TABLE `__PREFIX__fzly_user_detail` ADD COLUMN `proxy_level` int(10) DEFAULT '1' COMMENT '代理等级(默认青铜)';


--
-- 1.3.0更新sql
--
ALTER TABLE `__PREFIX__fzly_admission` ADD COLUMN `attractions_id` int(10) DEFAULT '0' COMMENT '所属景区';
ALTER TABLE `__PREFIX__fzly_order` ADD COLUMN `attractions_id` int(10) DEFAULT '0' COMMENT '景区id(用于统计管理账号下的订单)';
CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_admission_money_log` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `attractions_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '景区id',
    `admission_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门票id',
    `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '支付用户id',
    `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更余额',
    `before` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更前余额',
    `after` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更后余额',
    `y` varchar(100) DEFAULT NULL COMMENT '年',
    `m` varchar(100) DEFAULT NULL COMMENT '月',
    `d` varchar(100) DEFAULT NULL COMMENT '日',
    `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
    `updatetime` bigint(16) DEFAULT NULL COMMENT '修改时间',
    PRIMARY KEY (`id`) USING BTREE
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='景区收益表';
ALTER TABLE `__PREFIX__fzly_attractions` ADD COLUMN `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '收益';
ALTER TABLE `__PREFIX__fzly_attractions` ADD COLUMN  `name` varchar(100) DEFAULT NULL COMMENT '姓名';
ALTER TABLE `__PREFIX__fzly_attractions` ADD COLUMN `tel` varchar(100) DEFAULT NULL COMMENT '手机号';
ALTER TABLE `__PREFIX__fzly_attractions` ADD COLUMN `card` varchar(100) DEFAULT NULL COMMENT '身份证号';
ALTER TABLE `__PREFIX__fzly_attractions` ADD COLUMN `yy_image` varchar(100) DEFAULT NULL COMMENT '营业执照';
ALTER TABLE `__PREFIX__fzly_attractions` MODIFY COLUMN `status` enum('1','2','0') DEFAULT '0' COMMENT '审核状态:0=未审核,1=已审核,2=审核失败';
ALTER TABLE `__PREFIX__fzly_attractions` ADD COLUMN `refuse` varchar(100) DEFAULT NULL COMMENT '审核拒绝原因';
ALTER TABLE `__PREFIX__fzly_attractions` ADD COLUMN `user_id` int(10) DEFAULT '0' COMMENT '用户';

--
-- 1.3.1更新sql
--
CREATE TABLE IF NOT EXISTS `__PREFIX__fzly_user_admission` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `user_id` int(10) DEFAULT NULL COMMENT '用户id',
    `admission_id` int(10) DEFAULT NULL COMMENT '景区id',
    `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
    `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
    PRIMARY KEY (`id`) USING BTREE
    ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='景区管理账号表'