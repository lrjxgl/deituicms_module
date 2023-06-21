<?php
$content=<<<eof
CREATE TABLE `sky_mod_hongbao` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `total_money` decimal(9,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '红包总金额',
  `total_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包总数',
  `max_money` decimal(9,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '最大红包',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '截止时间',
  `iscreate` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '生成红包',
  `tpl` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT 'show.html',
  `isask` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `isfinish` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `ask` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `answer` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `joindesc` text CHARACTER SET utf8 COMMENT '参与方式',
  `content` mediumtext CHARACTER SET utf8,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_hongbao_day` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `money` decimal(6,2) NOT NULL DEFAULT '0.00' COMMENT '金额 分',
  `daytime` date NOT NULL DEFAULT '2019-02-24',
  `content` varchar(225) NOT NULL DEFAULT '',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1782 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_hongbao_html` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `word` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `content` mediumtext CHARACTER SET utf8,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='红包招商';
CREATE TABLE `sky_mod_hongbao_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hbid` int(10) unsigned NOT NULL,
  `money` decimal(7,2) unsigned NOT NULL DEFAULT '0.00',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `user_guest` varchar(225) NOT NULL DEFAULT '' COMMENT '用户留言',
  PRIMARY KEY (`id`),
  KEY `hbid` (`hbid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1861 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_hongbao_sendlog` (
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
) ENGINE=InnoDB AUTO_INCREMENT=399 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_hongbao_user` (
  `userid` int(10) unsigned NOT NULL,
  `money` decimal(7,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_hongbao_user_moneylog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `money` decimal(7,2) unsigned NOT NULL DEFAULT '0.00',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `typeid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2399 DEFAULT CHARSET=utf8mb4;

eof;
?>