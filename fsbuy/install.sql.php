<?php
$content=<<<eof
CREATE TABLE `sky_mod_fsbuy` (
  `fsid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fstype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '团购类型 1满团 2阶梯团',
  `title` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `viewnum` int(10) unsigned NOT NULL DEFAULT '0',
  `mp4url` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `createtime` datetime NOT NULL DEFAULT '2018-04-10 09:12:01',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `price` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `market_price` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `invite_money_max` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `invite_money` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `minnum` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '成团人数',
  `maxnum` int(10) unsigned NOT NULL DEFAULT '10' COMMENT '最大数',
  `buynum` int(10) unsigned NOT NULL DEFAULT '0',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `isksid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '款式',
  `endTime` datetime DEFAULT '2019-04-21 09:30:31' COMMENT '结束时间',
  `startTime` datetime NOT NULL DEFAULT '2019-04-21 09:30:31' COMMENT '开始时间',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `fsnote` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '备注',
  `step_config` varchar(225) NOT NULL DEFAULT '',
  `content` mediumtext CHARACTER SET utf8,
  PRIMARY KEY (`fsid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_fsbuy_backorder` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` int(10) unsigned NOT NULL DEFAULT '0',
  `fsid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2022-05-09 14:05:01',
  `money` decimal(9,2) NOT NULL DEFAULT '0.00',
  `content` varchar(225) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='返利记录';
CREATE TABLE `sky_mod_fsbuy_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(225) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='精品团购';
CREATE TABLE `sky_mod_fsbuy_invite_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `fsid` int(10) unsigned NOT NULL DEFAULT '0',
  `money` decimal(9,2) NOT NULL DEFAULT '0.00',
  `to_userid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fsid` (`fsid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_fsbuy_ks` (
  `ksid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fsid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `total_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '库存',
  `buy_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '购买数',
  `price` decimal(9,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '价格',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '图片',
  `createtime` datetime NOT NULL DEFAULT '2019-06-10 08:02:01',
  PRIMARY KEY (`ksid`),
  KEY `fsid` (`fsid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_fsbuy_order` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fsid` int(10) unsigned NOT NULL DEFAULT '0',
  `fstype` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2018-02-07 08:30:01',
  `money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ispay` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `address` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `nickname` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系人',
  `telephone` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系电话',
  `isreceived` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否收货',
  `express` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '快递',
  `israty` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ksids` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '款式id',
  `kstitle` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '款式产品',
  `ordercode` varchar(32) NOT NULL DEFAULT '' COMMENT '验证码',
  `recharge_id` int(10) unsigned NOT NULL,
  `paytype` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '支付方式',
  `invite_fsuserid` int(10) unsigned NOT NULL DEFAULT '0',
  `isback` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否返利了',
  `pin_success` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ksjson` text CHARACTER SET utf8,
  PRIMARY KEY (`orderid`),
  KEY `fsid` (`fsid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_fsbuy_order_code` (
  `orderid` int(10) unsigned NOT NULL DEFAULT '0',
  `ordercode` varchar(32) NOT NULL DEFAULT '',
  `isuse` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `checktime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`orderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='订单验证码';
CREATE TABLE `sky_mod_fsbuy_order_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2018-04-18 09:33:01',
  `content` text CHARACTER SET utf8,
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_fsbuy_order_raty` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fsid` int(10) unsigned NOT NULL DEFAULT '0',
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COMMENT='订单评价';
CREATE TABLE `sky_mod_fsbuy_view` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fsid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fsid` (`fsid`,`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=149 DEFAULT CHARSET=utf8;

eof;
?>