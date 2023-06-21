<?php
$content=<<<eof
CREATE TABLE `sky_mod_cfd` (
  `cfdid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `catid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `isfinish` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `endtime` datetime NOT NULL DEFAULT '2018-02-05 11:37:01' COMMENT '结束时间',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '图片',
  `videourl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '视频',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `needmoney` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '需要金额',
  `joinnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '众筹人数',
  `jionmoney` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '众筹金额',
  `gqmoney` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '股权最大投资额',
  `teamdesc` text CHARACTER SET utf8 COMMENT '团队介绍',
  `content` mediumtext CHARACTER SET utf8,
  PRIMARY KEY (`cfdid`),
  KEY `catid` (`catid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='众筹';
CREATE TABLE `sky_mod_cfd_order` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cfdid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2018-02-07 08:30:01',
  `money` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ispay` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `isback` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否退回',
  `rewardid` int(10) unsigned NOT NULL DEFAULT '0',
  `isreward` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否奖励',
  `typeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '投资类型 0普通投资 1股权投资',
  `reward_content` varchar(225) NOT NULL DEFAULT '',
  PRIMARY KEY (`orderid`),
  KEY `cfdid` (`cfdid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COMMENT='订单';
CREATE TABLE `sky_mod_cfd_reward` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `money` int(10) unsigned NOT NULL DEFAULT '0',
  `cfdid` int(10) unsigned NOT NULL DEFAULT '0',
  `typeid` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '类型 0普通 1股权',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `cfdid` (`cfdid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COMMENT='回报';

eof;
?>