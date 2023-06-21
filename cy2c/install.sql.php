<?php
$content=<<<eof
CREATE TABLE `sky_mod_cy2c_cart` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `productid` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  `createtime` datetime NOT NULL DEFAULT '2019-01-30 08:01:01' COMMENT '创建时间',
  `userid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `amount` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单数量',
  `ksid` int(11) NOT NULL DEFAULT '0' COMMENT '款式',
  `placeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '座位',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `createtime` (`createtime`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_cy2c_category` (
  `catid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderindex` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) CHARACTER SET utf8 DEFAULT '',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_cy2c_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shoptype` varchar(12) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '类型',
  `vipcard` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `md5code` varchar(255) NOT NULL DEFAULT 'sadasda',
  `retype` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付方式 0线上 1线下支付',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_cy2c_order` (
  `orderid` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单',
  `poid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderno` varchar(36) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '订单号',
  `status` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `israty` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否评论 0否 1是',
  `ispay` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否支付 1是 ',
  `userid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `isreceived` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否接收 0 否 1是',
  `money` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '订单价格',
  `paymoney` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '在线支付金额',
  `vipmoney` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '会员卡支付金额',
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
  `recharge_id` int(10) unsigned NOT NULL,
  `weight` decimal(9,2) unsigned DEFAULT '0.00',
  `placeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '座位',
  PRIMARY KEY (`orderid`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `orderno` (`orderno`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_cy2c_order_address` (
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
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_cy2c_order_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2018-07-17 09:01:01',
  `updatetime` datetime NOT NULL DEFAULT '2018-07-17 09:01:01',
  `content` mediumtext CHARACTER SET utf8,
  `placeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '座位',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_cy2c_order_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `createtime` datetime NOT NULL DEFAULT '2019-02-28 06:01:01',
  `orderid` bigint(20) NOT NULL DEFAULT '0',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `adminid` int(11) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_cy2c_order_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL DEFAULT '0',
  `poid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(11) unsigned NOT NULL DEFAULT '0',
  `productid` int(11) NOT NULL DEFAULT '0',
  `price` decimal(13,2) NOT NULL DEFAULT '0.00',
  `amount` int(11) NOT NULL DEFAULT '1',
  `createtime` datetime NOT NULL DEFAULT '2017-07-02 12:44:41',
  `ksid` int(11) NOT NULL DEFAULT '0' COMMENT '款式id',
  `iscomment` tinyint(4) NOT NULL DEFAULT '0',
  `raty_grade` tinyint(4) NOT NULL DEFAULT '0',
  `placeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '座位',
  `isfinish` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '完成 0未完成 1已完成 2已取消',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '完成 0未完成 1已完成 2已取消',
  `ispay` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `updatetime` datetime NOT NULL DEFAULT '2017-07-02 12:44:41' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`) USING BTREE,
  KEY `productid` (`productid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_cy2c_order_raty` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `createtime` datetime NOT NULL DEFAULT '2019-02-28 10:01:01' COMMENT '创建时间',
  `raty_express` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '快递',
  `raty_service` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '服务',
  `raty_quality` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '质量',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '内容',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_cy2c_place` (
  `placeid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '座位名称',
  `zw_num` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '座位数',
  `min_num` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '最低人数',
  `max_num` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '最多人数',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `Inservice` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '占用情况',
  PRIMARY KEY (`placeid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_cy2c_placeorder` (
  `poid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `createtime` datetime NOT NULL DEFAULT '2022-04-25 01:02:03',
  `updatetime` datetime NOT NULL DEFAULT '2022-04-25 01:02:03',
  `isfinish` tinyint(4) NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `placeid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`poid`),
  KEY `placeid` (`placeid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='分时订单';
CREATE TABLE `sky_mod_cy2c_plan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `placeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '座位',
  `title` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '预定名称',
  `num` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '人数',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `nickname` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '预订人',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '预定电话',
  `createtime` datetime NOT NULL DEFAULT '2019-04-03 08:57:01' COMMENT '创建时间',
  `plantime` datetime NOT NULL DEFAULT '2019-04-03 08:57:01' COMMENT '预定时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `placeid` (`placeid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_cy2c_product` (
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
  PRIMARY KEY (`id`),
  KEY `catid_status` (`catid`,`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=159 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_cy2c_product_data` (
  `id` int(10) unsigned NOT NULL,
  `content` mediumtext CHARACTER SET utf8,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_cy2c_product_ks` (
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

eof;
?>