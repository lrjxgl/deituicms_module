<?php
$content=<<<eof
CREATE TABLE `sky_mod_book` (
  `bookid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL DEFAULT '',
  `description` varchar(225) NOT NULL DEFAULT '',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ispay` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要密码',
  `money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00',
  `imgurl` varchar(225) NOT NULL DEFAULT '',
  `isprivate` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '私有',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '推荐',
  `content` mediumtext,
  `buy_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '销量',
  `fav_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数',
  PRIMARY KEY (`bookid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_book_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `bookid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(64) NOT NULL DEFAULT '',
  `haschild` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是有有下级',
  `description` varchar(225) NOT NULL DEFAULT '',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `orderindex` int(10) NOT NULL DEFAULT '0',
  `mp3url` varchar(225) NOT NULL DEFAULT '',
  `mp4url` varchar(225) NOT NULL DEFAULT '',
  `createtime` datetime NOT NULL DEFAULT '2018-03-17 20:26:01',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext,
  `pageindex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '推荐到首页',
  `fav_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '喜欢数',
  `comment_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  PRIMARY KEY (`id`),
  KEY `bookid` (`bookid`,`pid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_book_article_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `objectid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级评论',
  `createtime` datetime NOT NULL DEFAULT '2018-07-02 18:52:01' COMMENT '创建时间',
  `content` text COMMENT '内容',
  `ip` varchar(32) NOT NULL DEFAULT '' COMMENT 'ip',
  `ip_city` varchar(50) NOT NULL DEFAULT '' COMMENT '所在城市',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数',
  `imgsdata` text COMMENT '图集',
  PRIMARY KEY (`id`),
  KEY `objectid` (`objectid`,`status`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_book_article_data` (
  `id` int(10) unsigned NOT NULL,
  `content` mediumtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_book_article_log` (
  `logid` bigint(10) unsigned NOT NULL AUTO_INCREMENT,
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `bookid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(64) NOT NULL DEFAULT '',
  `description` varchar(225) NOT NULL DEFAULT '',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `orderindex` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `mp3url` varchar(225) NOT NULL DEFAULT '',
  `mp4url` varchar(225) NOT NULL DEFAULT '',
  `content` mediumtext,
  `createtime` datetime NOT NULL DEFAULT '2018-03-17 20:26:01',
  PRIMARY KEY (`logid`),
  KEY `id` (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_book_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `bookid` int(10) unsigned NOT NULL DEFAULT '0',
  `articleid` int(10) unsigned NOT NULL DEFAULT '0',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2018-01-28 20:35:01',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ip_addr` varchar(64) NOT NULL DEFAULT '',
  `ip` varchar(64) NOT NULL DEFAULT '',
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_book_note` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `bookid` int(10) unsigned NOT NULL DEFAULT '0',
  `articleid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2019-07-24 02:55:01',
  `content` text NOT NULL COMMENT '内容',
  PRIMARY KEY (`id`),
  KEY `bookid` (`bookid`) USING BTREE,
  KEY `a_u` (`articleid`,`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_book_order` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `bookid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2018-03-07 14:06:01',
  `isdelete` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`orderid`),
  KEY `ub` (`userid`,`bookid`) USING BTREE,
  KEY `bu` (`bookid`,`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

eof;
?>