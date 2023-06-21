<?php
$content=<<<eof
CREATE TABLE `sky_mod_sblog_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='版主';
CREATE TABLE `sky_mod_sblog_blog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_num` int(10) unsigned NOT NULL DEFAULT '0',
  `mp4url` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `createtime` datetime NOT NULL DEFAULT '2018-04-10 09:12:01',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `city` varchar(16) NOT NULL DEFAULT '',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `fav_num` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `gold` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '奖励',
  `imgsdata` text CHARACTER SET utf8 COMMENT '图集',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=237 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_sblog_blog_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `objectid` int(10) unsigned NOT NULL DEFAULT '0',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级评论',
  `createtime` datetime NOT NULL DEFAULT '2018-07-02 18:52:01',
  `content` text CHARACTER SET utf8,
  `ip` varchar(32) CHARACTER SET utf8 NOT NULL,
  `ip_city` varchar(50) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `objectid` (`objectid`,`status`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_sblog_blog_view` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `objectid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2019-01-09 12:20:21',
  PRIMARY KEY (`id`),
  KEY `objectid` (`objectid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=802 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_sblog_chat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` text,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_sblog_feeds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `blogid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '新鲜事id',
  `fuserid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`,`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=982 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_sblog_topic` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2019-05-08 05:48:01',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `isindex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '首页',
  `view_num` int(11) NOT NULL,
  `blog_num` int(11) NOT NULL,
  `description` varchar(225) NOT NULL,
  `imgurl` varchar(225) NOT NULL,
  `comment_num` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `title` (`title`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COMMENT='主题';
CREATE TABLE `sky_mod_sblog_topic_index` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `topicid` int(10) unsigned NOT NULL DEFAULT '0',
  `blogid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2019-05-08 05:48:01',
  PRIMARY KEY (`id`),
  KEY `topicid` (`topicid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COMMENT='主题-帖子';

eof;
?>