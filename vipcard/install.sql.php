<?php
$content=<<<eof
CREATE TABLE `sky_mod_vipcard` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nickname` varchar(12) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '手机',
  `idcard` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '身份证',
  `money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '金额',
  `total_money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '金额',
  `createtime` datetime NOT NULL DEFAULT '2019-03-31 01:02:03' COMMENT '办理时间',
  `updatetime` datetime NOT NULL DEFAULT '2019-03-31 01:02:03' COMMENT '更新时间',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '备注',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `telephone` (`telephone`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_vipcard_apply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nickname` varchar(12) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '手机',
  `idcard` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '身份证',
  `createtime` datetime NOT NULL DEFAULT '2019-03-31 01:02:03' COMMENT '办理时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `telephone` (`telephone`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_vipcard_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `cardid` int(11) NOT NULL DEFAULT '0' COMMENT '会员卡',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `createtime` datetime NOT NULL DEFAULT '2019-04-01 07:46:02' COMMENT '办理时间',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '备注',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_vipcard_option` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(12) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '平台金额',
  `paymoney` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '现金',
  `createtime` datetime NOT NULL DEFAULT '2019-03-31 01:02:03' COMMENT '办理时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_vipcard_recharge` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `cardid` int(11) NOT NULL DEFAULT '0' COMMENT '会员卡',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '平台金额',
  `paymoney` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '现金',
  `createtime` datetime NOT NULL DEFAULT '2019-04-01 07:33:31' COMMENT '充值时间',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '备注',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

eof;
?>