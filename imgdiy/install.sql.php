<?php
$content=<<<eof
CREATE TABLE `sky_mod_imgdiy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态 1 上线',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '名称',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '描述',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '效果图',
  `createtime` datetime NOT NULL DEFAULT '2018-11-09 19:40:01' COMMENT '创建时间',
  `imgtpl` text COMMENT '模板',
  `imgdata` text COMMENT '图片数据',
  `width` smallint(5) unsigned NOT NULL DEFAULT '640',
  `height` smallint(5) unsigned NOT NULL DEFAULT '1136',
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `bgimg` varchar(225) NOT NULL DEFAULT '' COMMENT '背景图片',
  `phpimgurl` varchar(225) NOT NULL DEFAULT '' COMMENT 'php设计图',
  PRIMARY KEY (`id`),
  KEY `createtime` (`createtime`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_imgdiy_bgimg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '名称',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态 1 上线',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '效果图',
  `createtime` datetime NOT NULL DEFAULT '2018-11-09 19:40:01' COMMENT '创建时间',
  `width` smallint(5) unsigned NOT NULL DEFAULT '640',
  `height` smallint(5) unsigned NOT NULL DEFAULT '1136',
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `catid` (`catid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_imgdiy_category` (
  `catid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(16) NOT NULL DEFAULT '' COMMENT '名称',
  `orderindex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_imgdiy_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `imgid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '名称',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态 1 上线',
  `orderindex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `createtime` datetime NOT NULL DEFAULT '2018-11-09 19:40:01' COMMENT '创建时间',
  `type` varchar(16) NOT NULL,
  `word` text,
  `size` smallint(5) unsigned NOT NULL DEFAULT '0',
  `x` smallint(5) unsigned NOT NULL DEFAULT '0',
  `y` smallint(5) unsigned NOT NULL DEFAULT '0',
  `color` varchar(12) NOT NULL DEFAULT '',
  `font` varchar(128) NOT NULL DEFAULT '',
  `imgurl` varchar(225) NOT NULL DEFAULT '',
  `w` smallint(5) unsigned NOT NULL DEFAULT '0',
  `h` smallint(5) unsigned NOT NULL DEFAULT '0',
  `angle` decimal(9,2) NOT NULL DEFAULT '0.00',
  `direction` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '方向 0横排 1竖排',
  `border` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `createtime` (`createtime`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_imgdiy_order` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `nickname` varchar(16) NOT NULL DEFAULT '' COMMENT '昵称',
  `telephone` varchar(16) NOT NULL DEFAULT '' COMMENT '手机',
  `address` varchar(64) NOT NULL DEFAULT '' COMMENT '收货地址',
  `money` decimal(9,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '金额',
  `price` decimal(9,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '价格',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '数量',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `typeid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`orderid`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_imgdiy_pic` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `md5key` varchar(64) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态 1 上线',
  `imgurl` varchar(64) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

eof;
?>