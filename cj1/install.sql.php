<?php
$content=<<<eof
CREATE TABLE `sky_mod_cj1` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '图片',
  `price` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '价值',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间',
  `need_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '需要人数',
  `join_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '参与人数',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  `isfinish` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否结束',
  `win_userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '中奖者',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `winlog` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `isask` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ask` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `answer` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `content` mediumtext CHARACTER SET utf8 COMMENT '内容',
  `isgold` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否奖励',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_cj1_giftlog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gday` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid_gday` (`userid`,`gday`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_cj1_html` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `word` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `content` mediumtext CHARACTER SET utf8,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='抽奖招商';
CREATE TABLE `sky_mod_cj1_order` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `use_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '使用时间',
  `isuse` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `dateline` decimal(16,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '时间',
  `objectid` int(10) unsigned NOT NULL DEFAULT '0',
  `iswin` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`orderid`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_cj1_statlog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `objectid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `fuserid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`,`objectid`) USING BTREE,
  KEY `fuserid` (`fuserid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8mb4 COMMENT='访问记录';
CREATE TABLE `sky_mod_cj1_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `gold` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_cj1_user_goldlog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `gold` int(11) NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `typeid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `userid_typeid` (`userid`,`typeid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8mb4;

eof;
?>