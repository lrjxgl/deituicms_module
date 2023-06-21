<?php
$content=<<<eof
CREATE TABLE `sky_mod_paotui` (
  `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` bigint(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2019-09-02 16:12:01' COMMENT '创建时间',
  `updatetime` datetime NOT NULL DEFAULT '2021-08-23 01:02:03' COMMENT '更新时间',
  `ispay` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `money` smallint(6) NOT NULL DEFAULT '0',
  `toaddrid` bigint(10) unsigned NOT NULL DEFAULT '0',
  `fromaddrid` bigint(10) unsigned NOT NULL DEFAULT '0',
  `fromaddr` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `toaddr` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `goodsmoney` decimal(9,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品价格',
  `weight` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '重量',
  `typeid` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '类型',
  `senderid` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '接单者',
  `recharge_id` int(11) NOT NULL,
  `paytype` varchar(16) NOT NULL DEFAULT '',
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `cityid` int(10) unsigned NOT NULL,
  `fromapi` varchar(12) NOT NULL DEFAULT '' COMMENT '扩展单',
  `fromapi_params` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '扩展参数',
  `israty` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` text CHARACTER SET utf8 COMMENT '内容',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_paotui_addr` (
  `addrid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `truename` varchar(16) NOT NULL DEFAULT '' COMMENT '名称',
  `address` varchar(128) NOT NULL DEFAULT '0' COMMENT '地址',
  `telephone` varchar(18) NOT NULL DEFAULT '' COMMENT '电话',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000',
  PRIMARY KEY (`addrid`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_paotui_bankcard` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `senderid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `yhk_name` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '银行名称',
  `yhk_haoma` varchar(24) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '银行卡号码',
  `yhk_huming` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '银行卡户名',
  `telephone` varchar(18) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '电话',
  `yhk_address` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '开户地址',
  `paytype` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '支付方式  支付宝 银行卡 微信 ',
  PRIMARY KEY (`id`),
  KEY `senderid` (`senderid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_paotui_category` (
  `catid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(16) NOT NULL DEFAULT '' COMMENT '名称',
  `typeid` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '服务类型',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '图片',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `orderindex` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_paotui_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `per_money` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '抽成',
  `croncode` varchar(64) NOT NULL DEFAULT '' COMMENT '计划任务密钥',
  `min_money` tinyint(3) unsigned NOT NULL DEFAULT '3',
  `fee_desc` varchar(255) NOT NULL DEFAULT '' COMMENT '收费标准',
  `lazy_time` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '派单延时',
  `morecity` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_paotui_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `dateline` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_paotui_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `ptid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `senderid` int(10) unsigned NOT NULL DEFAULT '0',
  `israty` tinyint(4) NOT NULL DEFAULT '0',
  `raty_grade` tinyint(3) unsigned NOT NULL DEFAULT '10',
  `raty_content` varchar(225) NOT NULL DEFAULT '',
  `money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `createtime` datetime NOT NULL DEFAULT '2019-09-03 16:59:01',
  `fromapi` varchar(32) NOT NULL DEFAULT '' COMMENT '扩展单',
  `fromapi_params` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '扩展单参数',
  `updatetime` datetime NOT NULL DEFAULT '2021-08-23 06:09:01',
  `isfine` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '罚款',
  `fine_money` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '罚款金额',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_paotui_order_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `senderid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2019-02-01 10:02:02',
  `imgurl` varchar(225) NOT NULL DEFAULT '',
  `content` varchar(225) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_paotui_raty` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ptid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `createtime` datetime NOT NULL DEFAULT '2019-02-28 10:01:01' COMMENT '创建时间',
  `grade` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '快递',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '内容',
  PRIMARY KEY (`id`),
  KEY `ptid` (`ptid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_paotui_sender` (
  `senderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `nickname` varchar(16) NOT NULL DEFAULT '',
  `imgurl` varchar(225) NOT NULL DEFAULT '/static/images/user_head.jpg',
  `usercard` varchar(225) NOT NULL DEFAULT '',
  `truename` varchar(16) NOT NULL DEFAULT '',
  `userno` varchar(16) NOT NULL DEFAULT '',
  `telephone` varchar(18) NOT NULL DEFAULT '',
  `address` varchar(64) NOT NULL DEFAULT '',
  `money` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `income` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2019-05-11 04:38:01',
  `isauth` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `description` varchar(225) NOT NULL DEFAULT '',
  `isvip` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否员工',
  PRIMARY KEY (`senderid`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_paotui_sender_apply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `nickname` varchar(16) NOT NULL DEFAULT '',
  `userhead` varchar(225) NOT NULL DEFAULT '',
  `usercard` varchar(225) NOT NULL DEFAULT '',
  `truename` varchar(16) NOT NULL DEFAULT '',
  `userno` varchar(16) NOT NULL DEFAULT '',
  `telephone` varchar(18) NOT NULL DEFAULT '',
  `address` varchar(64) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2019-11-05 04:14:01',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_paotui_sender_moneylog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `senderid` int(10) unsigned NOT NULL DEFAULT '0',
  `money` decimal(9,2) NOT NULL DEFAULT '0.00',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `senderid` (`senderid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_paotui_sender_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dateline` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `status` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '状态  0未读',
  `senderid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `content` text CHARACTER SET utf8 COMMENT '内容',
  `linkurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '链接',
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '通知' COMMENT '小标题',
  PRIMARY KEY (`id`),
  KEY `senderid` (`senderid`,`dateline`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=469 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_paotui_sender_safephone` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `senderid` int(10) unsigned NOT NULL DEFAULT '0',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `senderid` (`senderid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

eof;
?>