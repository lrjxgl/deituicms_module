<?php
$content=<<<eof
CREATE TABLE `sky_mod_s2c_cart` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `productid` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  `createtime` datetime NOT NULL DEFAULT '2019-01-30 08:01:01' COMMENT '创建时间',
  `userid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `amount` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单数量',
  `ksid` int(11) NOT NULL DEFAULT '0' COMMENT '款式',
  `teamid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '团队',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `createtime` (`createtime`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_s2c_category` (
  `catid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderindex` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) CHARACTER SET utf8 DEFAULT '',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_s2c_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `per_money` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '抽成比例',
  `fctype` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '分成模式 0订单分成 1产品分成',
  `earnest_money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '保证金',
  `out_time` tinyint(3) unsigned NOT NULL DEFAULT '24' COMMENT '晚单',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_s2c_done` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `teamid` int(10) unsigned NOT NULL DEFAULT '0',
  `scid` int(10) unsigned NOT NULL DEFAULT '0',
  `smonth` int(10) unsigned NOT NULL DEFAULT '0',
  `money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2019-03-14 03:28:01',
  `income` decimal(11,2) unsigned DEFAULT '0.00' COMMENT '收入',
  PRIMARY KEY (`id`),
  KEY `teamid` (`teamid`) USING BTREE,
  KEY `scid` (`scid`) USING BTREE,
  KEY `smonth` (`smonth`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_s2c_order` (
  `orderid` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单',
  `orderno` varchar(36) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '订单号',
  `status` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `israty` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否评论 0否 1是',
  `ispay` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否支付 1是 ',
  `userid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `isreceived` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否接收 0 否 1是',
  `money` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '订单价格',
  `user_address_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收货地址',
  `express_no` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '物流号',
  `goods_money` decimal(15,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '产品价格',
  `express_money` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '快递价格',
  `discount_money` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '折扣价格',
  `coupon_id` int(11) NOT NULL DEFAULT '0' COMMENT '优惠券',
  `coupon_money` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '优惠券价格',
  `comment` varchar(225) CHARACTER SET utf8 NOT NULL COMMENT '评论内容',
  `paytype` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '支付方式',
  `whocancel` varchar(16) CHARACTER SET utf8 NOT NULL COMMENT '谁取消的',
  `daySn` smallint(6) NOT NULL DEFAULT '0' COMMENT '每日订单排序',
  `total_num` smallint(5) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2017-07-02 12:43:01',
  `finishtime` datetime NOT NULL DEFAULT '2017-07-02 12:43:01' COMMENT '订单完成时间',
  `recharge_id` int(10) unsigned NOT NULL,
  `weight` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `scid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '社区',
  `teamid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '团队',
  `sendtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`orderid`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `orderno` (`orderno`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_s2c_order_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) unsigned NOT NULL DEFAULT '0',
  `truename` char(8) CHARACTER SET utf8 DEFAULT NULL,
  `telephone` char(12) CHARACTER SET utf8 DEFAULT NULL,
  `address` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  `createtime` datetime NOT NULL DEFAULT '2017-07-02 12:43:01',
  `province_id` int(11) NOT NULL DEFAULT '0',
  `city_id` int(11) NOT NULL DEFAULT '0',
  `town_id` int(11) NOT NULL DEFAULT '0',
  `teamid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '社区',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_s2c_order_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2018-07-17 09:01:01',
  `updatetime` datetime NOT NULL DEFAULT '2018-07-17 09:01:01',
  `content` mediumtext CHARACTER SET utf8,
  `teamid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '社区',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_s2c_order_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `createtime` datetime NOT NULL DEFAULT '2019-02-28 06:01:01',
  `orderid` bigint(20) NOT NULL DEFAULT '0',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `adminid` int(11) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_s2c_order_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) unsigned NOT NULL DEFAULT '0',
  `productid` int(11) NOT NULL DEFAULT '0',
  `price` decimal(13,2) NOT NULL DEFAULT '0.00',
  `amount` int(11) NOT NULL DEFAULT '1',
  `createtime` datetime NOT NULL DEFAULT '2017-07-02 12:44:41',
  `ksid` int(11) NOT NULL DEFAULT '0' COMMENT '款式id',
  `iscomment` tinyint(4) NOT NULL DEFAULT '0',
  `raty_grade` tinyint(4) NOT NULL DEFAULT '0',
  `teamid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '社区',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`) USING BTREE,
  KEY `productid` (`productid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_s2c_order_raty` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `createtime` datetime NOT NULL DEFAULT '2019-02-28 10:01:01' COMMENT '创建时间',
  `raty_express` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '快递',
  `raty_service` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '服务',
  `raty_quality` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '质量',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '内容',
  `scid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '社区',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_s2c_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `catid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '图片',
  `price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '价格',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态 1上架',
  `weight` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `createtime` datetime NOT NULL DEFAULT '2019-01-30 08:01:01' COMMENT '创建时间',
  `updatetime` datetime NOT NULL DEFAULT '2019-01-30 08:01:01' COMMENT '更新时间',
  `ks_label_name` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '颜色' COMMENT '款式名称',
  `ks_label_size` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '尺寸' COMMENT '款式尺寸',
  `month_buy_num` int(10) unsigned NOT NULL DEFAULT '0',
  `videourl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `isnew` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '最新',
  `ishot` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '最热',
  `isrecommend` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '推荐',
  `total_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '库存',
  `buy_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '销量',
  `lower_price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '促销价',
  `market_price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '市场价',
  `isksid` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否有款式',
  `per_money` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '抽成比例',
  PRIMARY KEY (`id`),
  KEY `catid_status` (`catid`,`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_s2c_product_data` (
  `id` int(10) unsigned NOT NULL,
  `content` mediumtext CHARACTER SET utf8,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_s2c_product_ks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `productid` int(11) unsigned NOT NULL COMMENT '产品',
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `size` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '尺寸',
  `price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00',
  `createtime` datetime NOT NULL DEFAULT '2019-01-30 08:01:01' COMMENT '创建时间',
  `total_num` int(11) NOT NULL DEFAULT '0',
  `buy_num` int(11) NOT NULL DEFAULT '0',
  `imgurl` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `productid` (`productid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_s2c_shequ` (
  `scid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '社区名称',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `address` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000' COMMENT '纬度',
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000' COMMENT '经度',
  `teamid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '团长',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '/static/images/no_image.jpg',
  PRIMARY KEY (`scid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_s2c_team` (
  `teamid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `nickname` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '昵称',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '电话',
  `address` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `userhead` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '头像',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `scid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '小区',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '收入',
  `total_money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '总收入',
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000',
  PRIMARY KEY (`teamid`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_s2c_team_apply` (
  `teamid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `nickname` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '昵称',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '电话',
  `address` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `userhead` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '头像',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `usercard` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '身份证',
  `usernum` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '身份证号码',
  `wxhao` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '微信号',
  `shequ` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '小区名称',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000',
  PRIMARY KEY (`teamid`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_s2c_team_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `teamid` int(10) unsigned NOT NULL DEFAULT '0',
  `money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `typeid` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '类型 1收入 2支出',
  `createtime` datetime NOT NULL DEFAULT '2019-03-14 03:28:01',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '内容',
  PRIMARY KEY (`id`),
  KEY `teamid` (`teamid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_s2c_vendor` (
  `vdid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `nickname` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系人',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '电话',
  `address` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `yyzz` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '营业执照',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '店招',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`vdid`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_s2c_vendor_apply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `nickname` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系人',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '电话',
  `address` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `yyzz` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '营业执照',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '业务介绍',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

eof;
?>