<?php
$content=<<<eof
CREATE TABLE `sky_mod_zupu_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `typeid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员';
CREATE TABLE `sky_mod_zupu_chat_msg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `ppid` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) NOT NULL DEFAULT '' COMMENT '描述',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `createtime` datetime NOT NULL DEFAULT '2022-01-18 09:40:41',
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='聊天消息';
CREATE TABLE `sky_mod_zupu_group` (
  `gid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '描述',
  `address` varchar(225) NOT NULL DEFAULT '' COMMENT '描述',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `imgurl` varchar(225) NOT NULL,
  `isindex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '上首页',
  `nickname` varchar(16) NOT NULL DEFAULT '',
  `telephone` varchar(15) NOT NULL DEFAULT '',
  `xing` varchar(12) NOT NULL DEFAULT '',
  `beifen` varchar(64) NOT NULL DEFAULT '' COMMENT '辈分',
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `lng` decimal(9,6) DEFAULT '0.000000',
  `zuzhang` varchar(12) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  PRIMARY KEY (`gid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='组';
CREATE TABLE `sky_mod_zupu_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gid` int(10) unsigned NOT NULL DEFAULT '0',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `isindex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '上首页',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '标题',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '主图',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '描述',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `content` text NOT NULL COMMENT '内容',
  `createtime` datetime NOT NULL DEFAULT '2022-01-17 01:02:03',
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='资讯';
CREATE TABLE `sky_mod_zupu_people` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gid` int(10) unsigned NOT NULL DEFAULT '0',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `nickname` varchar(12) NOT NULL DEFAULT '',
  `gender` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '性别 1男 2女',
  `age` datetime NOT NULL DEFAULT '2021-03-03 18:01:01',
  `description` varchar(225) NOT NULL DEFAULT '',
  `address` varchar(64) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `provinceid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '省',
  `cityid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '市',
  `districtid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '区县',
  `townid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '镇',
  `telephone` varchar(18) NOT NULL DEFAULT '' COMMENT '电话',
  `imgurl` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

eof;
?>