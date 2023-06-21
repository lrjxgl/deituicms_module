<?php
$content=<<<eof
CREATE TABLE `sky_mod_xiangqin_biaobai` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `touserid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `content` varchar(225) NOT NULL DEFAULT '' COMMENT '内容',
  `createtime` datetime NOT NULL DEFAULT '2022-05-11 02:03:05',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `touserid` (`touserid`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='表白';
CREATE TABLE `sky_mod_xiangqin_friend` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `touserid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `touserid` (`touserid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='朋友';
CREATE TABLE `sky_mod_xiangqin_join` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='加入表';
CREATE TABLE `sky_mod_xiangqin_people` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `truename` varchar(12) NOT NULL DEFAULT '' COMMENT '姓名',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '头像',
  `birthday` date NOT NULL DEFAULT '2022-05-11' COMMENT '出生年月',
  `gender` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '性别',
  `you_desc` varchar(225) NOT NULL DEFAULT '' COMMENT '对象要求',
  `self_desc` varchar(225) NOT NULL DEFAULT '' COMMENT '自我介绍',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `grade` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `bb_num` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '表白',
  `isauth` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '实名认证',
  `income` tinyint(3) unsigned NOT NULL DEFAULT '5' COMMENT '年收入',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '电话',
  `videourl` varchar(128) NOT NULL DEFAULT '0' COMMENT '视频',
  `imgdata` varchar(512) NOT NULL DEFAULT '' COMMENT '图集',
  `has_car` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `has_house` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `zhiye` varchar(32) NOT NULL DEFAULT '' COMMENT '职业',
  `town_id` int(10) unsigned NOT NULL DEFAULT '0',
  `city_id` int(10) unsigned NOT NULL DEFAULT '0',
  `province_id` int(10) unsigned NOT NULL DEFAULT '0',
  `address` varchar(32) NOT NULL DEFAULT '',
  `createtime` datetime NOT NULL DEFAULT '2022-05-15 12:01:02',
  `updatetime` datetime NOT NULL DEFAULT '2022-05-15 12:01:02',
  `content` text NOT NULL,
  PRIMARY KEY (`id`,`zhiye`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='人';
CREATE TABLE `sky_mod_xiangqin_people_new` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `truename` varchar(12) NOT NULL DEFAULT '' COMMENT '姓名',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '头像',
  `birthday` date NOT NULL DEFAULT '2022-05-11' COMMENT '出生年月',
  `gender` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '性别',
  `you_desc` varchar(225) NOT NULL DEFAULT '' COMMENT '对象要求',
  `self_desc` varchar(225) NOT NULL DEFAULT '' COMMENT '自我介绍',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `grade` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `bb_num` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '表白',
  `isauth` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '实名认证',
  `income` tinyint(3) unsigned NOT NULL DEFAULT '5' COMMENT '年收入',
  `has_house` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `has_car` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '电话',
  `videourl` varchar(128) NOT NULL DEFAULT '0' COMMENT '视频',
  `imgdata` varchar(512) NOT NULL DEFAULT '' COMMENT '图集',
  `zhiye` varchar(32) NOT NULL DEFAULT '' COMMENT '职业',
  `town_id` int(10) unsigned NOT NULL DEFAULT '0',
  `city_id` int(10) unsigned NOT NULL DEFAULT '0',
  `province_id` int(10) unsigned NOT NULL DEFAULT '0',
  `address` varchar(32) NOT NULL DEFAULT '',
  `createtime` datetime NOT NULL DEFAULT '2022-05-15 12:01:02',
  `updatetime` datetime NOT NULL DEFAULT '2022-05-15 12:01:02',
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='人';
CREATE TABLE `sky_mod_xiangqin_zhaohu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `touserid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `content` varchar(225) NOT NULL DEFAULT '' COMMENT '内容',
  `createtime` datetime NOT NULL DEFAULT '2022-05-11 02:03:05',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `touserid` (`touserid`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='打招呼';

eof;
?>