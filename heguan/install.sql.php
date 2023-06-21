<?php
$content=<<<eof
CREATE TABLE `sky_mod_heguan_admin` (
  `adminid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `heid` int(10) unsigned NOT NULL,
  `nickname` varchar(12) NOT NULL DEFAULT '' COMMENT '名称',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '电话',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '简介',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `createtime` datetime NOT NULL DEFAULT '2022-09-14 06:54:01',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '主图',
  `isown` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '主管',
  PRIMARY KEY (`adminid`),
  KEY `heid` (`heid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='河道管理';
CREATE TABLE `sky_mod_heguan_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `heid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '标题',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '简介',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `createtime` datetime NOT NULL DEFAULT '2022-09-14 06:54:01',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '主图',
  `imgsdata` varchar(512) NOT NULL DEFAULT '' COMMENT '图集',
  `content` text COMMENT '内容',
  PRIMARY KEY (`id`),
  KEY `heid` (`heid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='河道文章';
CREATE TABLE `sky_mod_heguan_guest` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `heid` int(10) unsigned NOT NULL DEFAULT '0',
  `typeid` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '投诉类型',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '标题',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '简介',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `createtime` datetime NOT NULL DEFAULT '2022-09-14 06:54:01',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '主图',
  `imgsdata` varchar(512) NOT NULL DEFAULT '' COMMENT '图集',
  PRIMARY KEY (`id`),
  KEY `heid` (`heid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='河道投诉';
CREATE TABLE `sky_mod_heguan_he` (
  `heid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '名称',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '简介',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级',
  `createtime` datetime NOT NULL DEFAULT '2022-09-14 06:54:01',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '主图',
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `address` varchar(64) NOT NULL DEFAULT '',
  `imgsdata` varchar(512) NOT NULL DEFAULT '' COMMENT '图集',
  `content` text COMMENT '内容',
  PRIMARY KEY (`heid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='河道';

eof;
?>