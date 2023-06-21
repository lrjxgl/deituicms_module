<?php
$content=<<<eof
CREATE TABLE `sky_mod_shopmap` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '用户',
  `catid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类',
  `title` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系电话',
  `address` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `createtime` datetime NOT NULL DEFAULT '2018-12-07 11:32:01',
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '图片',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_num` int(10) unsigned NOT NULL DEFAULT '0',
  `nickname` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext CHARACTER SET utf8,
  `mapCanvas` text CHARACTER SET utf8,
  `wxhao` varchar(225) NOT NULL DEFAULT '' COMMENT '微信号',
  `wbhao` varchar(225) NOT NULL DEFAULT '' COMMENT '微博号',
  `qqhao` varchar(32) NOT NULL DEFAULT '' COMMENT 'qq号',
  `taobao` varchar(225) NOT NULL DEFAULT '' COMMENT '淘宝',
  `pdd` varchar(225) NOT NULL DEFAULT '' COMMENT '拼多多',
  `dyhao` varchar(225) NOT NULL DEFAULT '' COMMENT '抖音号',
  `wxgzh` varchar(225) NOT NULL DEFAULT '' COMMENT '微信公众号',
  `kshao` varchar(225) NOT NULL DEFAULT '' COMMENT '快手号',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COMMENT='商家位置';
CREATE TABLE `sky_mod_shopmap_apply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '用户',
  `catid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类',
  `title` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系电话',
  `address` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `createtime` datetime NOT NULL DEFAULT '2018-12-07 11:32:01',
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `wxhao` varchar(225) NOT NULL DEFAULT '' COMMENT '微信号',
  `wbhao` varchar(225) NOT NULL DEFAULT '' COMMENT '微博号',
  `qqhao` varchar(32) NOT NULL DEFAULT '' COMMENT 'qq号',
  `taobao` varchar(225) NOT NULL DEFAULT '' COMMENT '淘宝',
  `pdd` varchar(225) NOT NULL DEFAULT '' COMMENT '拼多多',
  `dyhao` varchar(225) NOT NULL DEFAULT '' COMMENT '抖音号',
  `wxgzh` varchar(225) NOT NULL DEFAULT '' COMMENT '微信公众号',
  `kshao` varchar(225) NOT NULL DEFAULT '' COMMENT '快手号',
  `hbmoney` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '图片',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_num` int(10) unsigned NOT NULL DEFAULT '0',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0',
  `nickname` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `content` mediumtext CHARACTER SET utf8,
  `mapCanvas` text CHARACTER SET utf8,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COMMENT='商家位置';
CREATE TABLE `sky_mod_shopmap_category` (
  `catid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderindex` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) CHARACTER SET utf8 DEFAULT '',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_shopmap_hongbao_sendlog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `money` decimal(9,2) NOT NULL DEFAULT '0.00',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `status` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `isdelete` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `msg` varchar(255) CHARACTER SET utf8 NOT NULL,
  `content` text CHARACTER SET utf8,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COMMENT='红包推送记录';
CREATE TABLE `sky_mod_shopmap_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `createtime` datetime NOT NULL DEFAULT '2018-12-27 11:12:14' COMMENT '创建时间',
  `userid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `apply_num` int(10) unsigned NOT NULL DEFAULT '0',
  `pass_num` int(10) unsigned NOT NULL DEFAULT '0',
  `money` decimal(9,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='参与用户';

eof;
?>