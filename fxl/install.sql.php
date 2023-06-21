<?php
$content=<<<eof
CREATE TABLE `sky_mod_fxl` (
  `fxlid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `endtime` datetime NOT NULL DEFAULT '2018-02-05 11:37:01' COMMENT '结束时间',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '图片',
  `videourl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '视频',
  `needmoney` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '需要金额',
  `joinnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '众筹人数',
  `joinmoney` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '众筹金额',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '联系电话',
  `bill_shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `nickname` varchar(15) NOT NULL DEFAULT '' COMMENT '联系人',
  `address` varchar(128) NOT NULL DEFAULT '' COMMENT '地址',
  `content` mediumtext CHARACTER SET utf8,
  PRIMARY KEY (`fxlid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_fxl_cert` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(127) NOT NULL DEFAULT '' COMMENT '名称',
  `fxlid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2018-02-07 08:30:01',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` text COMMENT '内容',
  PRIMARY KEY (`id`),
  KEY `fxlid` (`fxlid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_fxl_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fxlid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `createtime` datetime NOT NULL DEFAULT '2018-02-07 08:30:01',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` text COMMENT '内容',
  PRIMARY KEY (`id`),
  KEY `cfdid` (`fxlid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_fxl_order` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fxlid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2018-02-07 08:30:01',
  `money` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `nickname` varchar(32) NOT NULL DEFAULT '',
  `ispay` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) NOT NULL DEFAULT '' COMMENT '留言',
  PRIMARY KEY (`orderid`),
  KEY `cfdid` (`fxlid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4;

eof;
?>