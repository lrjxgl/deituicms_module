<?php
$content=<<<eof
CREATE TABLE `sky_mod_elsearch_table` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tablename` varchar(16) NOT NULL DEFAULT '',
  `title` varchar(32) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `show_url` varchar(128) NOT NULL DEFAULT '',
  `orderindex` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `updatetime` datetime NOT NULL DEFAULT '2022-03-01 18:01:02',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COMMENT='全站表';
CREATE TABLE `sky_mod_elsearch_topic` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tablename` varchar(16) NOT NULL DEFAULT '',
  `objectid` bigint(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(128) NOT NULL DEFAULT '',
  `description` varchar(225) NOT NULL DEFAULT '',
  `catid` bigint(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext,
  PRIMARY KEY (`id`),
  KEY `objectid` (`objectid`),
  FULLTEXT KEY `content` (`content`)
) ENGINE=InnoDB AUTO_INCREMENT=1377 DEFAULT CHARSET=utf8mb4 COMMENT='全站搜索';

eof;
?>