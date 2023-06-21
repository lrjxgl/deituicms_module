<?php
$content=<<<eof
CREATE TABLE `sky_mod_fsw_activity` (
  `actid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '介绍',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '图片',
  `createtime` datetime NOT NULL DEFAULT '2022-08-05 12:01:01',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `isfinish` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `user_num` int(10) unsigned NOT NULL DEFAULT '0',
  `sday` datetime NOT NULL DEFAULT '2022-08-06 08:01:02' COMMENT '比赛日期',
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `lng` decimal(9,6) NOT NULL,
  `content` text COMMENT '详情',
  PRIMARY KEY (`actid`),
  KEY `mid` (`mid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='赛事活动';
CREATE TABLE `sky_mod_fsw_join` (
  `joinid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `actid` int(10) unsigned NOT NULL DEFAULT '0',
  `mid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `weight` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '重量',
  `grade` tinyint(4) NOT NULL DEFAULT '0' COMMENT '积分',
  `nickname` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `telephone` varchar(15) NOT NULL DEFAULT '',
  `createtime` datetime NOT NULL DEFAULT '2022-08-05 13:30:33',
  `imgsdata` varchar(512) NOT NULL DEFAULT '',
  `description` varchar(200) NOT NULL DEFAULT '',
  `ischeck` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`joinid`),
  KEY `actid` (`actid`),
  KEY `mid` (`mid`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='赛事活动';
CREATE TABLE `sky_mod_fsw_match` (
  `mid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '介绍',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '图片',
  `createtime` datetime NOT NULL DEFAULT '2022-08-05 12:01:01',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `user_num` int(10) unsigned NOT NULL DEFAULT '0',
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `nickname` varchar(12) NOT NULL DEFAULT '',
  `telephone` varchar(15) NOT NULL DEFAULT '',
  `address` varchar(64) NOT NULL DEFAULT '',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `grade` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text,
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='赛事';
CREATE TABLE `sky_mod_fsw_match_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `grade` int(10) unsigned NOT NULL DEFAULT '0',
  `join_num` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='赛事活动用户';
CREATE TABLE `sky_mod_fsw_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `grade` int(10) unsigned NOT NULL DEFAULT '0',
  `join_num` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `nickname` varchar(12) NOT NULL DEFAULT '',
  `telephone` varchar(15) NOT NULL DEFAULT '',
  `user_head` varchar(128) NOT NULL DEFAULT '/static/images/user_head.jpg',
  `address` varchar(128) NOT NULL DEFAULT '',
  `description` varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `grade` (`grade`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='全部统计';

eof;
?>