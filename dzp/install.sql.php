<?php
$content=<<<eof
CREATE TABLE `sky_mod_dzp_event` (
  `eventid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(225) NOT NULL DEFAULT '' COMMENT '名称',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `stime` int(10) unsigned NOT NULL DEFAULT '0',
  `etime` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `join_num` tinyint(3) unsigned zerofill NOT NULL DEFAULT '000',
  `limit_num` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `description` varchar(225) NOT NULL DEFAULT '',
  `imgurl` varchar(225) NOT NULL DEFAULT '',
  `gailv` int(10) unsigned NOT NULL DEFAULT '10000' COMMENT '概率基数',
  `isauth` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `addronly` varchar(32) NOT NULL DEFAULT '',
  `tpl` varchar(64) NOT NULL DEFAULT '',
  `content` text,
  PRIMARY KEY (`eventid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='大转盘活动';
CREATE TABLE `sky_mod_dzp_event_join` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `eventid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `max_num` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `use_num` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `eventid` (`eventid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8 COMMENT='大转盘-参与者';
CREATE TABLE `sky_mod_dzp_event_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `eventid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `productid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `eventid` (`eventid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=196 DEFAULT CHARSET=utf8 COMMENT='大转盘记录';
CREATE TABLE `sky_mod_dzp_order` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `eventid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `productid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `sendtype` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `reward_num` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `nickname` varchar(16) NOT NULL DEFAULT '',
  `telephone` varchar(15) NOT NULL,
  `address` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`orderid`),
  KEY `eventid` (`eventid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=utf8 COMMENT='大转盘-订单';
CREATE TABLE `sky_mod_dzp_product` (
  `productid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `eventid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(225) NOT NULL DEFAULT '' COMMENT '名称',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `description` varchar(225) NOT NULL DEFAULT '',
  `imgurl` varchar(225) NOT NULL DEFAULT '',
  `orderindex` tinyint(3) NOT NULL DEFAULT '0',
  `isorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `reward_desc` varchar(64) NOT NULL DEFAULT '',
  `gailv` int(10) unsigned NOT NULL DEFAULT '0',
  `ptype` varchar(16) NOT NULL DEFAULT '',
  `sendtype` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `reward_num` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `max_num` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '奖品总数',
  `sold_num` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`productid`),
  KEY `eventid` (`eventid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='大转盘-奖品';

eof;
?>