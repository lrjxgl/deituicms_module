<?php
$content=<<<eof
CREATE TABLE `sky_mod_kefu` (
  `kfid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `orderindex` smallint(5) unsigned NOT NULL DEFAULT '0',
  `user_head` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT '/static/images/user_head.jpg',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `tablename` varchar(16) NOT NULL DEFAULT '',
  `objectid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`kfid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_kefu_msg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kfid` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `xfrom` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `fileurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `filetype` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `videoimg` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `filesize` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `filename` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `isread` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `author` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `ukey` varchar(12) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `kfid` (`kfid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_kefu_msg_index` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kfid` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `tablename` varchar(16) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `xfrom` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `fileurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `filetype` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `filesize` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `filename` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `isread` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `videoimg` varchar(225) NOT NULL DEFAULT '',
  `author` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `ukey` varchar(12) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `kfid` (`kfid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_kefu_spmsg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kfid` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `fileurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `filetype` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `videoimg` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `filesize` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `filename` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `isread` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `author` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `ukey` varchar(12) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `kfid` (`kfid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_kefu_spmsg_index` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kfid` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `tablename` varchar(16) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `fileurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `filetype` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `filesize` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `filename` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `isread` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `videoimg` varchar(225) NOT NULL DEFAULT '',
  `author` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `ukey` varchar(12) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `kfid` (`kfid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

eof;
?>