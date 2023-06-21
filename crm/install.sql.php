<?php
$content=<<<eof
CREATE TABLE `sky_mod_crm_kehu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `nickname` varchar(16) NOT NULL DEFAULT '' COMMENT '名字',
  `telephone` varchar(16) NOT NULL DEFAULT '' COMMENT '电话',
  `gender` varchar(12) NOT NULL DEFAULT '' COMMENT '性别',
  `adddress` varchar(225) NOT NULL DEFAULT '' COMMENT '地址',
  `wxhao` varchar(21) NOT NULL DEFAULT '' COMMENT '微信号',
  `qqhao` varchar(21) NOT NULL DEFAULT '' COMMENT 'qq号',
  `weibo` varchar(21) NOT NULL DEFAULT '' COMMENT '微博',
  `wx_freind` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '微信好友',
  `qq_freind` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'qq好友',
  `createtime` datetime NOT NULL DEFAULT '2019-08-15 12:38:01',
  `lasttime` datetime NOT NULL DEFAULT '2019-08-15 12:38:01',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '简介',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_crm_kehu_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `kehuid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2019-08-15 12:38:01',
  `content` varchar(225) NOT NULL DEFAULT '' COMMENT '内容',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

eof;
?>