<?php
$content=<<<eof
CREATE TABLE `sky_mod_group` (
  `gid` int(11) NOT NULL AUTO_INCREMENT,
  `catid` int(11) NOT NULL,
  `siteid` int(10) unsigned NOT NULL DEFAULT '0',
  `grade` int(11) NOT NULL DEFAULT '0' COMMENT '积分',
  `gname` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `glogo` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '/static/images/group_logo.jpg',
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `keywords` varchar(255) CHARACTER SET utf8 NOT NULL,
  `description` varchar(225) CHARACTER SET utf8 NOT NULL,
  `userid` int(11) unsigned NOT NULL DEFAULT '0',
  `dateline` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `user_num` int(11) NOT NULL DEFAULT '0',
  `follow_num` int(11) NOT NULL DEFAULT '0',
  `banner` varchar(225) CHARACTER SET utf8 NOT NULL,
  `is_recommend` tinyint(4) NOT NULL DEFAULT '0',
  `isnew` tinyint(4) NOT NULL DEFAULT '0',
  `ishot` tinyint(4) NOT NULL DEFAULT '0',
  `topic_num` int(11) NOT NULL DEFAULT '0' COMMENT '主题数',
  `content` mediumtext CHARACTER SET utf8 COMMENT '内容',
  PRIMARY KEY (`gid`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_group_apply` (
  `gid` int(11) NOT NULL AUTO_INCREMENT,
  `catid` int(11) NOT NULL,
  `gname` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `glogo` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '/static/images/group_logo.jpg',
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `keywords` varchar(255) CHARACTER SET utf8 NOT NULL,
  `description` varchar(225) CHARACTER SET utf8 NOT NULL,
  `userid` int(11) unsigned NOT NULL DEFAULT '0',
  `dateline` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `siteid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`gid`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_group_chat` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `chat_userid` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `chat_roomid` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `gid` int(10) unsigned NOT NULL DEFAULT '0',
  `nickname` varchar(16) CHARACTER SET utf8 DEFAULT '',
  `user_head` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `content` text CHARACTER SET utf8,
  `siteid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `chat_roomid` (`chat_roomid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COMMENT='聊天';
CREATE TABLE `sky_mod_group_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `newsid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '新鲜事id',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `dateline` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `siteid` int(10) unsigned DEFAULT '0',
  `gid` int(11) NOT NULL DEFAULT '0',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) NOT NULL DEFAULT '' COMMENT '内容',
  `imgurl` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `imgsdata` varchar(512) NOT NULL DEFAULT '',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`) USING BTREE,
  KEY `newsid` (`newsid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_group_tags` (
  `tagid` int(11) NOT NULL AUTO_INCREMENT,
  `tagname` varchar(20) CHARACTER SET utf8 NOT NULL,
  `title` varchar(225) CHARACTER SET utf8 NOT NULL,
  `keywords` varchar(225) CHARACTER SET utf8 NOT NULL,
  `description` varchar(225) CHARACTER SET utf8 NOT NULL,
  `gid` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `dateline` int(11) NOT NULL,
  `userid` int(11) unsigned NOT NULL DEFAULT '0',
  `orderindex` tinyint(4) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `siteid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`tagid`),
  KEY `gid` (`gid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_group_title` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL DEFAULT '1',
  `dateline` int(11) NOT NULL,
  `gid` int(11) unsigned NOT NULL DEFAULT '1',
  `tagid` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) unsigned NOT NULL DEFAULT '0',
  `title` varchar(225) CHARACTER SET utf8 NOT NULL,
  `keywords` varchar(225) CHARACTER SET utf8 NOT NULL,
  `description` varchar(225) CHARACTER SET utf8 NOT NULL,
  `imgurl` varchar(128) NOT NULL DEFAULT '',
  `last_time` int(11) NOT NULL DEFAULT '0',
  `comment_num` int(11) NOT NULL DEFAULT '0',
  `click_num` int(11) NOT NULL DEFAULT '0',
  `isrecommend` tinyint(4) NOT NULL DEFAULT '0',
  `isnew` tinyint(4) NOT NULL DEFAULT '0',
  `ishot` tinyint(4) NOT NULL DEFAULT '0',
  `love_num` int(11) NOT NULL DEFAULT '0',
  `fav_num` int(10) unsigned NOT NULL DEFAULT '0',
  `isding` int(11) NOT NULL DEFAULT '0',
  `videourl` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '视频链接',
  `isindex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '首页展示',
  `siteid` int(10) unsigned NOT NULL DEFAULT '0',
  `tags` varchar(64) NOT NULL DEFAULT '',
  `open_data` varchar(128) NOT NULL DEFAULT '' COMMENT '开放接入',
  `imgsdata` text CHARACTER SET utf8,
  `content` mediumtext CHARACTER SET utf8 COMMENT '内容',
  PRIMARY KEY (`id`),
  KEY `gid_tagid` (`gid`,`tagid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_group_title_love` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `newsid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'tid',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `siteid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tid` (`newsid`,`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_group_type` (
  `catid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `orderindex` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `siteid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_group_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dateline` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `userid` int(11) unsigned NOT NULL DEFAULT '0',
  `gid` int(11) NOT NULL,
  `user_group` tinyint(4) NOT NULL DEFAULT '1',
  `topic_num` int(11) NOT NULL,
  `comment_num` int(11) NOT NULL,
  `isfound` tinyint(1) DEFAULT '0',
  `isadmin` tinyint(1) DEFAULT '0',
  `siteid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `gid_userid` (`gid`,`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_group_user_apply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `gid` int(10) unsigned NOT NULL DEFAULT '0',
  `daytime` datetime NOT NULL COMMENT '时间',
  `content` text CHARACTER SET utf8 COMMENT '理由',
  `siteid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_group_utag` (
  `tagid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL DEFAULT '',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`tagid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='用户标签';
CREATE TABLE `sky_mod_group_utag_index` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tagid` int(10) unsigned NOT NULL DEFAULT '0',
  `objectid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tagid` (`tagid`),
  KEY `objectid` (`objectid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='用户标签';

eof;
?>