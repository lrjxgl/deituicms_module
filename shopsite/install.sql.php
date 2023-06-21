<?php
$content=<<<eof
CREATE TABLE `sky_mod_shopsite` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `typeid` int(10) unsigned NOT NULL,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `vipdomain` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `domain` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `pc_tpl` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT 'shop/themes/pcIndex',
  `wap_tpl` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT 'shop/themes/1wap',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `price` int(10) unsigned NOT NULL DEFAULT '0',
  `sitename` varchar(20) CHARACTER SET utf8 NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `keywords` varchar(255) CHARACTER SET utf8 NOT NULL,
  `description` varchar(225) CHARACTER SET utf8 NOT NULL,
  `logo` varchar(225) CHARACTER SET utf8 NOT NULL,
  `statjs` varchar(225) CHARACTER SET utf8 NOT NULL,
  `gztime` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `banner` varchar(225) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_shopsite_order` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `money` decimal(9,2) NOT NULL DEFAULT '0.00',
  `eyear` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2018-07-01 10:02:01' COMMENT '创建时间',
  `ispay` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`orderid`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_shopsite_tplconfig` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `skinsdir` varchar(32) CHARACTER SET utf8 NOT NULL,
  `skinsdata` longtext CHARACTER SET utf8,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

eof;
?>