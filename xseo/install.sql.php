<?php
$content=<<<eof
CREATE TABLE `sky_mod_xseo_amazon_keyword` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL DEFAULT '',
  `view_num` int(11) NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2022-11-27 01:02:03',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `kfrom` varchar(255) NOT NULL DEFAULT '',
  `unionword` varchar(128) NOT NULL DEFAULT '',
  `total_num` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COMMENT='关键词';
CREATE TABLE `sky_mod_xseo_amazon_product` (
  `productid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `asin` varchar(32) NOT NULL DEFAULT '',
  `title` varchar(256) NOT NULL DEFAULT '',
  `imgurl` varchar(128) NOT NULL DEFAULT '',
  `linkurl` varchar(128) NOT NULL DEFAULT '',
  `raty` decimal(3,1) NOT NULL DEFAULT '0.0',
  `price` decimal(9,2) NOT NULL DEFAULT '0.00',
  `lower_price` decimal(9,2) NOT NULL DEFAULT '0.00',
  `createtime` datetime NOT NULL DEFAULT '2022-11-27 01:02:03',
  `updatetime` datetime NOT NULL DEFAULT '2022-11-27 01:02:03',
  `pageindex` smallint(6) NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `raty_num` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`productid`),
  KEY `asin` (`asin`)
) ENGINE=InnoDB AUTO_INCREMENT=405 DEFAULT CHARSET=utf8 COMMENT='产品';
CREATE TABLE `sky_mod_xseo_amazon_stole_index` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `productid` int(10) unsigned NOT NULL DEFAULT '0',
  `pageindex` smallint(6) NOT NULL DEFAULT '0',
  `keyword` varchar(32) NOT NULL DEFAULT '',
  `widget_id` varchar(32) NOT NULL DEFAULT '',
  `createtime` datetime NOT NULL DEFAULT '2022-11-27 01:02:03',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(64) NOT NULL DEFAULT '',
  `asin` varchar(32) NOT NULL DEFAULT '',
  `xfrom` varchar(16) NOT NULL DEFAULT 'search',
  `area` varchar(16) NOT NULL DEFAULT 'zh',
  PRIMARY KEY (`id`),
  KEY `productid` (`productid`),
  KEY `asin` (`asin`)
) ENGINE=InnoDB AUTO_INCREMENT=4602 DEFAULT CHARSET=utf8 COMMENT='产品-采集记录';
CREATE TABLE `sky_mod_xseo_amazon_stole_temp` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `content` mediumtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='采集内容';

eof;
?>