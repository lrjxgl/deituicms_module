<?php
$content=<<<eof
CREATE TABLE `sky_mod_mscard` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nickname` varchar(12) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '手机',
  `idcard` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '身份证',
  `money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '金额',
  `grade` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `gold` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '金币',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商家',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '办理时间',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '备注',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_mscard_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `cardid` int(11) NOT NULL DEFAULT '0' COMMENT '会员卡',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '金额',
  `grade` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `gold` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '金币',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '办理时间',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '备注',
  `type` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_mscard_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `cardid` int(11) NOT NULL DEFAULT '0' COMMENT '会员卡',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '金额',
  `grade` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `gold` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '金币',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '办理时间',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_mscard_shop` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(11) NOT NULL DEFAULT '0' COMMENT '店铺ID',
  `shopname` varchar(25) CHARACTER SET utf8 NOT NULL COMMENT '店铺名字',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '审核状态 1未审核 2已审核',
  `logo` varchar(225) CHARACTER SET utf8 NOT NULL COMMENT '/static/images/shop_logo.jpg',
  `address` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000' COMMENT '纬度',
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000' COMMENT '经度',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00',
  `content` text CHARACTER SET utf8 COMMENT '简介',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

eof;
?>