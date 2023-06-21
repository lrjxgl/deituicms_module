<?php
$content=<<<eof
CREATE TABLE `sky_mod_search_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `threads` smallint(6) unsigned NOT NULL DEFAULT '20' COMMENT '线程数',
  `steps` tinyint(6) unsigned NOT NULL DEFAULT '9' COMMENT '采集深度',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_search_domain` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '主题',
  `domain` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `basedomain` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '一级域名',
  `url` varchar(300) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '首页',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间',
  `step` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '第几级',
  `selfsite` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1 只采集自己当前域名的站 0.采集主域名及子域名 2.采集所有',
  PRIMARY KEY (`id`),
  KEY `url` (`url`(255),`dateline`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_search_sphinxcount` (
  `id` int(10) unsigned NOT NULL,
  `max_doc_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_search_spider` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(300) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `islock` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '锁 1是 0否',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间',
  `step` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '第几级',
  `domain` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `nolink` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是没链接',
  `basedomain` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '一级域名',
  PRIMARY KEY (`id`),
  KEY `url` (`url`(255),`dateline`) USING BTREE,
  KEY `islock` (`islock`,`step`,`dateline`) USING BTREE,
  KEY `domain` (`domain`,`islock`,`step`,`dateline`) USING BTREE,
  KEY `basedomain` (`basedomain`,`islock`,`step`,`dateline`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_search_topic` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(300) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `islock` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '锁 1是 0否',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间',
  `last_time` int(10) unsigned NOT NULL DEFAULT '0',
  `step` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '第几级',
  `title` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '主题',
  `keywords` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '关键词',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `grade` int(10) unsigned NOT NULL DEFAULT '0',
  `domain` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `basedomain` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '一级域名',
  `content` mediumtext CHARACTER SET utf8,
  PRIMARY KEY (`id`),
  KEY `url` (`url`(255),`dateline`) USING BTREE,
  FULLTEXT KEY `title` (`title`,`content`) /*!50100 WITH PARSER `ngram` */ 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

eof;
?>