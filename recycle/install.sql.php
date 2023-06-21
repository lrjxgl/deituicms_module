<?php
$content=<<<eof
CREATE TABLE `sky_mod_recycle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `createtime` datetime NOT NULL DEFAULT '2018-10-14 09:37:01' COMMENT '创建时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `description` varchar(225) NOT NULL DEFAULT '描述',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '书店',
  `nickname` varchar(12) NOT NULL DEFAULT '' COMMENT '昵称',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '手机',
  `address` varchar(64) NOT NULL DEFAULT '' COMMENT '地址',
  `freetime` varchar(64) NOT NULL DEFAULT '' COMMENT '空闲时间',
  `finish_time` datetime NOT NULL DEFAULT '2018-10-14 09:37:01' COMMENT '完成订单',
  `israty` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `shopid` (`shopid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_recycle_guest` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `createtime` datetime NOT NULL DEFAULT '2019-02-23 05:31:01' COMMENT '创建时间',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户2',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `author` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `ukey` varchar(12) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_recycle_guestindex` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `createtime` datetime NOT NULL DEFAULT '2019-02-23 05:31:01' COMMENT '创建时间',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户2',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `author` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `ukey` varchar(12) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_recycle_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `recycleid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2021-06-29 20:46:01',
  `content` varchar(225) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `recycleid` (`recycleid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='订单日志';
CREATE TABLE `sky_mod_recycle_raty` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `recycleid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2021-06-30 06:34:01',
  `raty_grade` decimal(3,1) unsigned NOT NULL DEFAULT '0.0',
  `raty_content` varchar(225) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`),
  KEY `recycleid` (`recycleid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='回收评价';
CREATE TABLE `sky_mod_recycle_shop` (
  `shopid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '店名',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '简介',
  `imgurl` varchar(225) NOT NULL DEFAULT 'static/images/no_image.jpg' COMMENT '图片',
  `address` varchar(64) NOT NULL DEFAULT '' COMMENT '地址',
  `createtime` datetime NOT NULL DEFAULT '2017-06-18 18:09:01',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '手机',
  `nickname` varchar(15) NOT NULL DEFAULT '' COMMENT '店主',
  `lat` decimal(9,6) NOT NULL,
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`shopid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_recycle_shop_apply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '店名',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '简介',
  `imgurl` varchar(225) NOT NULL DEFAULT 'static/images/no_image.jpg' COMMENT '图片',
  `address` varchar(64) NOT NULL DEFAULT '' COMMENT '地址',
  `createtime` datetime NOT NULL DEFAULT '2017-06-18 18:09:01',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '手机',
  `nickname` varchar(15) NOT NULL DEFAULT '' COMMENT '店主',
  `lat` decimal(9,6) NOT NULL,
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `userno` varchar(20) NOT NULL DEFAULT '',
  `usercard` varchar(225) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_recycle_shop_price` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2021-06-30 06:34:01',
  `content` varchar(32) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='商家-回收价格';

eof;
?>