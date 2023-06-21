<?php
$content=<<<eof
CREATE TABLE `sky_mod_bzy_cron_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dtime` date NOT NULL DEFAULT '2021-09-04',
  `eventid` int(10) unsigned NOT NULL DEFAULT '0',
  `etype` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `eventid_dtime` (`eventid`,`dtime`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8 COMMENT='计划';
CREATE TABLE `sky_mod_bzy_event` (
  `eventid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(225) NOT NULL DEFAULT '' COMMENT '名称',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '前台管理者',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `stime` int(10) unsigned NOT NULL DEFAULT '0',
  `etime` int(10) unsigned NOT NULL DEFAULT '0',
  `etype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0初赛 1决赛',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `join_num` tinyint(3) unsigned zerofill NOT NULL DEFAULT '000',
  `limit_num` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `description` varchar(225) NOT NULL DEFAULT '',
  `banner` varchar(255) NOT NULL DEFAULT '',
  `imgurl` varchar(225) NOT NULL DEFAULT '',
  `tpl` varchar(64) NOT NULL DEFAULT '',
  `content` varchar(5000) NOT NULL DEFAULT '',
  `reward` varchar(5000) NOT NULL DEFAULT '',
  `rule` varchar(5000) NOT NULL DEFAULT '',
  `isfinish` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `orderindex` smallint(5) unsigned NOT NULL DEFAULT '0',
  `first_win_num` tinyint(3) unsigned NOT NULL DEFAULT '10',
  `last_win_num` tinyint(3) unsigned NOT NULL DEFAULT '10',
  PRIMARY KEY (`eventid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='博状元活动';
CREATE TABLE `sky_mod_bzy_event_join` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `eventid` int(10) unsigned NOT NULL DEFAULT '0',
  `dtime` date NOT NULL DEFAULT '2021-09-04',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `max_num` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `use_num` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `grade` int(10) unsigned NOT NULL DEFAULT '0',
  `etype` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `eventid` (`eventid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8 COMMENT='博状元-参与者';
CREATE TABLE `sky_mod_bzy_event_join_stat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `eventid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `eventid` (`eventid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='博状元-参与者';
CREATE TABLE `sky_mod_bzy_event_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `eventid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `productid` int(10) unsigned NOT NULL DEFAULT '0',
  `num` varchar(15) NOT NULL DEFAULT '' COMMENT '筛子',
  `res` varchar(225) NOT NULL DEFAULT '' COMMENT '结果',
  `dtime` date NOT NULL DEFAULT '2021-09-11',
  PRIMARY KEY (`id`),
  KEY `eventid` (`eventid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=246 DEFAULT CHARSET=utf8 COMMENT='博状元-记录';
CREATE TABLE `sky_mod_bzy_order` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `createtime` datetime NOT NULL DEFAULT '2021-09-09 02:03:04',
  `eventid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `productid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `sendtype` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `reward_num` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `nickname` varchar(16) NOT NULL DEFAULT '',
  `telephone` varchar(15) NOT NULL,
  `address` varchar(64) NOT NULL DEFAULT '',
  `joinid` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`orderid`),
  KEY `eventid` (`eventid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8 COMMENT='大博状元-订单';
CREATE TABLE `sky_mod_bzy_product` (
  `productid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `eventid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(225) NOT NULL DEFAULT '' COMMENT '名称',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `description` varchar(225) NOT NULL DEFAULT '',
  `imgurl` varchar(225) NOT NULL DEFAULT '',
  `orderindex` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `isorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `reward_desc` varchar(64) NOT NULL DEFAULT '',
  `gailv` varchar(32) NOT NULL DEFAULT '',
  `ptype` varchar(16) NOT NULL DEFAULT '',
  `sendtype` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `reward_num` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `max_num` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '奖品总数',
  `sold_num` int(10) unsigned NOT NULL DEFAULT '0',
  `etype` tinyint(3) unsigned DEFAULT '0' COMMENT '赛事 0初赛 1决赛',
  PRIMARY KEY (`productid`),
  KEY `eventid` (`eventid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='博状元-奖品';
CREATE TABLE `sky_mod_bzy_rank` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `eventid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `grade` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `eventid` (`eventid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8 COMMENT='博状元-排行';

eof;
?>