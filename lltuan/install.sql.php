<?php
$content=<<<eof
CREATE TABLE `sky_mod_lltuan_group` (
  `gid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `placeid` int(10) unsigned NOT NULL DEFAULT '0',
  `productid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `description` varchar(225) NOT NULL DEFAULT '',
  `imgurl` varchar(225) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '审核状态',
  `group_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '拼团状态',
  `join_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '参与数量',
  `finish_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '实际成交',
  `finish_time` datetime NOT NULL DEFAULT '2022-07-27 10:01:02',
  `isfinish` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2022-07-27 10:01:02',
  `address` varchar(64) NOT NULL DEFAULT '',
  `dotime` date NOT NULL DEFAULT '2022-07-27',
  `dj_money` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '定金',
  `content` varchar(1024) NOT NULL DEFAULT '',
  PRIMARY KEY (`gid`),
  KEY `placeid` (`placeid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='拼团';
CREATE TABLE `sky_mod_lltuan_order` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '审核状态',
  `num` tinyint(4) NOT NULL DEFAULT '0' COMMENT '参与人数',
  `money` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `createtime` datetime NOT NULL DEFAULT '2022-07-27 10:01:02',
  `nickname` varchar(12) NOT NULL DEFAULT '' COMMENT '昵称',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '电话',
  `address` varchar(64) NOT NULL DEFAULT '' COMMENT '地址',
  PRIMARY KEY (`orderid`),
  KEY `gid` (`gid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='服务';
CREATE TABLE `sky_mod_lltuan_place` (
  `placeid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `address` varchar(64) NOT NULL DEFAULT '' COMMENT '地址',
  `createtime` datetime NOT NULL DEFAULT '2022-07-27 10:01:02',
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000' COMMENT '纬度',
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000' COMMENT '精度',
  PRIMARY KEY (`placeid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='位置';
CREATE TABLE `sky_mod_lltuan_product` (
  `productid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '审核状态',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '描述',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '图片',
  `price` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `createtime` datetime NOT NULL DEFAULT '2022-07-27 10:01:02',
  `setdata` varchar(512) NOT NULL DEFAULT '',
  PRIMARY KEY (`productid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='服务';

eof;
?>