<?php
$content=<<<eof
CREATE TABLE `sky_mod_diypage_page` (
  `page_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `page_content` text COMMENT '页面内容',
  `createtime` datetime NOT NULL DEFAULT '2021-08-07 21:00:01',
  `updatetime` datetime NOT NULL DEFAULT '2021-08-07 21:00:01',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='页面diy-用户定义页面';
CREATE TABLE `sky_mod_diypage_ui` (
  `ui_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `ui_key` varchar(32) NOT NULL DEFAULT '' COMMENT 'ui名称',
  `createtime` datetime NOT NULL DEFAULT '2021-08-07 21:00:01',
  `updatetime` datetime NOT NULL DEFAULT '2021-08-07 21:00:01',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(50) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  PRIMARY KEY (`ui_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='页面diy-ui组件';
CREATE TABLE `sky_mod_diypage_userpage` (
  `up_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `page_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `page_content` text COMMENT '页面内容',
  `page_data` text COMMENT '页面数据',
  `createtime` datetime NOT NULL DEFAULT '2021-08-07 21:00:01',
  `updatetime` datetime NOT NULL DEFAULT '2021-08-07 21:00:01',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`up_id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `page_id` (`page_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='页面diy-用户定义页面';
CREATE TABLE `sky_mod_diypage_userpage_ui` (
  `id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `ui_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'ui',
  `up_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户页面',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `ui_key` varchar(32) NOT NULL DEFAULT '' COMMENT 'ui名称',
  `content` varchar(225) NOT NULL DEFAULT '' COMMENT '名称',
  `createtime` datetime NOT NULL DEFAULT '2021-08-07 21:00:01',
  `updatetime` datetime NOT NULL DEFAULT '2021-08-07 21:00:01',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='页面diy-用户设置';

eof;
?>