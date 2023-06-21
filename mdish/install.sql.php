<?php
$content=<<<eof
CREATE TABLE `sky_mod_mdish_lottery` (
  `ltid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(32) NOT NULL DEFAULT '',
  `description` varchar(225) NOT NULL DEFAULT '',
  `imgurl` varchar(225) NOT NULL DEFAULT '',
  `price` decimal(9,2) NOT NULL DEFAULT '0.00',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `isnew` tinyint(1) unsigned DEFAULT '0',
  `ishot` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isrecommend` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `sday` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ltid`),
  KEY `shopid` (`shopid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='抽奖';
CREATE TABLE `sky_mod_mdish_lottery_join` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `sday` int(10) unsigned NOT NULL DEFAULT '0',
  `address` varchar(64) NOT NULL DEFAULT '' COMMENT '地址',
  `nickname` varchar(16) NOT NULL DEFAULT '' COMMENT '联系人',
  `telephone` varchar(16) NOT NULL DEFAULT '' COMMENT '联系电话',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`,`sday`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='抽奖用户';
CREATE TABLE `sky_mod_mdish_lottery_setwin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sday` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='开奖记录';
CREATE TABLE `sky_mod_mdish_lottery_win` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `sday` int(10) unsigned NOT NULL DEFAULT '0',
  `address` varchar(64) NOT NULL DEFAULT '' COMMENT '地址',
  `nickname` varchar(16) NOT NULL DEFAULT '' COMMENT '联系人',
  `telephone` varchar(16) NOT NULL DEFAULT '' COMMENT '联系电话',
  `ltid` int(11) NOT NULL DEFAULT '0',
  `isfinish` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`,`sday`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='中奖用户';
CREATE TABLE `sky_mod_mdish_love` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `productid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `productid` (`productid`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='喜欢';
CREATE TABLE `sky_mod_mdish_product` (
  `productid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(32) NOT NULL DEFAULT '',
  `description` varchar(225) NOT NULL DEFAULT '',
  `imgurl` varchar(225) NOT NULL DEFAULT '',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `isnew` tinyint(1) unsigned DEFAULT '0',
  `ishot` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isrecommend` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `grade` decimal(11,4) unsigned NOT NULL DEFAULT '0.0000',
  `mp4url` varchar(225) NOT NULL DEFAULT '',
  `imgsdata` text,
  PRIMARY KEY (`productid`),
  KEY `shopid` (`shopid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='产品';
CREATE TABLE `sky_mod_mdish_shop` (
  `shopid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL DEFAULT '',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `description` varchar(225) NOT NULL DEFAULT '',
  `imgurl` varchar(225) NOT NULL DEFAULT '',
  `address` varchar(225) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `nickname` varchar(16) NOT NULL DEFAULT '',
  `telephone` varchar(15) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`shopid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商家';
CREATE TABLE `sky_mod_mdish_view` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `productid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `productid` (`productid`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='浏览';

eof;
?>