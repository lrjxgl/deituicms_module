<?php
$content=<<<eof
CREATE TABLE `sky_mod_h5video` (
  `vid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL,
  `width` int(10) unsigned NOT NULL DEFAULT '0',
  `height` int(10) unsigned NOT NULL DEFAULT '0',
  `bgmp3` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '背景音乐',
  `bgcolor` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `deltime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2019-04-10 07:14:12',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '推荐',
  `istpl` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '模板',
  `istts` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '字语音播报',
  PRIMARY KEY (`vid`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_h5video_animate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `animate` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '动画类名',
  `content` text CHARACTER SET utf8,
  `tpldata` text CHARACTER SET utf8,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_h5video_music` (
  `musicid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类',
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `url` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`musicid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_h5video_page` (
  `pageid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `vid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `title` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `orderindex` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pageid`),
  KEY `vid` (`vid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_h5video_page_item` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `vid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `title` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `itype` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '类型',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '图片',
  `linkurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '内容',
  `x` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT 'x轴',
  `y` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT 'y轴',
  `w` decimal(7,2) unsigned NOT NULL DEFAULT '100.00',
  `h` decimal(7,2) NOT NULL DEFAULT '100.00',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `orderindex` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `animate` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `styleid` int(10) unsigned NOT NULL DEFAULT '0',
  `zindex` int(10) unsigned NOT NULL DEFAULT '9',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `fontsize` tinyint(3) unsigned NOT NULL DEFAULT '14',
  `color` varchar(9) CHARACTER SET utf8 NOT NULL DEFAULT '#333',
  `textalign` varchar(12) CHARACTER SET utf8 NOT NULL DEFAULT 'left',
  `pluginid` int(10) unsigned NOT NULL DEFAULT '0',
  `itemcss` text CHARACTER SET utf8 COMMENT 'css内容',
  PRIMARY KEY (`id`),
  KEY `vid` (`vid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=202 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_h5video_plugin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '图片',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext CHARACTER SET utf8 COMMENT '样式内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COMMENT='h5-插件库';
CREATE TABLE `sky_mod_h5video_style` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` text CHARACTER SET utf8 COMMENT '样式内容',
  `tpldata` text CHARACTER SET utf8 COMMENT '模板',
  `stype` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT 'div',
  `classData` text CHARACTER SET utf8 COMMENT 'css类数据',
  `cssClass` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'css类',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;

eof;
?>