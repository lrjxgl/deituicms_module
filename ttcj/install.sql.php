<?php
$content=<<<eof
CREATE TABLE `sky_mod_ttcj` (
  `cjid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '标题',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '图片',
  `isopen` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否开奖',
  `createtime` datetime NOT NULL DEFAULT '2018-05-23 14:44:01' COMMENT '创建时间',
  `endtime` datetime NOT NULL DEFAULT '2018-05-23 14:44:01' COMMENT '创建时间',
  `join_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '参与人数',
  `win_userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '中奖者',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext CHARACTER SET utf8 COMMENT '内容',
  PRIMARY KEY (`cjid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_ttcj_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cjid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '抽奖',
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '标题',
  `join_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '参与人数',
  `money` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `cjid` (`cjid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_ttcj_join` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cjid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '抽奖',
  `userid` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '标题',
  `createtime` datetime NOT NULL DEFAULT '2018-05-23 14:44:01' COMMENT '创建时间',
  `address` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '手机',
  `nickname` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '昵称',
  PRIMARY KEY (`id`),
  KEY `cjid` (`cjid`,`userid`) USING BTREE,
  KEY `userid` (`userid`,`cjid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_ttcj_order` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cjid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '抽奖',
  `userid` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '标题',
  `createtime` datetime NOT NULL DEFAULT '2018-05-23 14:44:01' COMMENT '创建时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `address` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '手机',
  `nickname` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '昵称',
  `itemid` int(10) unsigned NOT NULL DEFAULT '0',
  `item_title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `item_money` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`orderid`),
  KEY `cjid` (`cjid`,`userid`) USING BTREE,
  KEY `userid` (`userid`,`cjid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

eof;
?>