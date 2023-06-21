<?php
$content=<<<eof
CREATE TABLE `sky_mod_zhuli` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '标题',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `description` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `is_temp` tinyint(1) NOT NULL DEFAULT '0',
  `starttime` int(11) NOT NULL DEFAULT '0',
  `endtime` int(11) NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `market_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `max_zlmoney` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最大的助力',
  `zl_min` smallint(6) unsigned NOT NULL DEFAULT '1',
  `zl_max` smallint(6) unsigned NOT NULL DEFAULT '100',
  `zlmoney` tinyint(3) unsigned DEFAULT '0' COMMENT '助力金额比 单位分',
  `tj_num` int(10) unsigned NOT NULL DEFAULT '0',
  `tj_money` decimal(10,0) unsigned NOT NULL DEFAULT '0',
  `tj_user` int(10) unsigned NOT NULL DEFAULT '0',
  `buy_num` int(10) unsigned NOT NULL DEFAULT '0',
  `ruledesc` text CHARACTER SET utf8 COMMENT '参与规则',
  `award` text CHARACTER SET utf8 COMMENT '奖品',
  PRIMARY KEY (`id`),
  KEY `shopid_status` (`status`) USING BTREE,
  KEY `shopappid_status` (`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_zhuli_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext CHARACTER SET utf8 COMMENT '内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_zhuli_go` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `joinid` int(11) NOT NULL DEFAULT '0' COMMENT '参与',
  `userid` int(11) NOT NULL DEFAULT '0',
  `zlid` int(11) NOT NULL DEFAULT '0' COMMENT '助力',
  `dateline` int(11) NOT NULL DEFAULT '0' COMMENT '助力时间',
  `zlmoney` int(11) NOT NULL DEFAULT '0' COMMENT '助力金额',
  PRIMARY KEY (`id`),
  KEY `zlid` (`zlid`) USING BTREE,
  KEY `userid_zlid` (`userid`,`zlid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_zhuli_join` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `zlid` int(11) NOT NULL DEFAULT '0' COMMENT '助力',
  `dateline` int(11) NOT NULL DEFAULT '0' COMMENT '助力时间',
  `zlnum` int(11) NOT NULL DEFAULT '0' COMMENT '助力人数',
  `zlmoney` int(11) NOT NULL DEFAULT '0' COMMENT '助力金额',
  `isfinish` tinyint(4) NOT NULL DEFAULT '0' COMMENT '完成',
  `lasttime` int(11) NOT NULL DEFAULT '0' COMMENT '完成时间',
  PRIMARY KEY (`id`),
  KEY `zlid` (`zlid`) USING BTREE,
  KEY `userid_zlid` (`userid`,`zlid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_zhuli_order` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `money` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `zlid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '产品',
  `createtime` datetime NOT NULL DEFAULT '2017-08-04 20:13:00' COMMENT '创建时间',
  `address` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `nickname` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系人',
  `telephone` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系电话',
  `isreceived` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否收货',
  `express` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '快递',
  `ispay` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付状态',
  `comm` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`orderid`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `zlid` (`zlid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_zhuli_shaidan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2017-11-03 08:54:11',
  `orderid` int(10) unsigned NOT NULL DEFAULT '0',
  `zlid` int(10) unsigned NOT NULL DEFAULT '0',
  `ordermoney` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `imgsdata` text CHARACTER SET utf8,
  `videourl` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '视频链接',
  `content` mediumtext CHARACTER SET utf8 COMMENT '内容',
  `gold` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `zlid` (`zlid`) USING BTREE,
  KEY `orderid` (`orderid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

eof;
?>