<?php
$content=<<<eof
CREATE TABLE `sky_mod_sjsj_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sold_money` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '买断价格',
  `pt_per` int(10) unsigned NOT NULL DEFAULT '20' COMMENT '平台抽成',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='配置';
CREATE TABLE `sky_mod_sjsj_guest` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `createtime` datetime NOT NULL DEFAULT '2019-02-23 05:31:01' COMMENT '创建时间',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `touserid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户2',
  `objectid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类信息',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `author` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `objectid_userid` (`objectid`,`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8mb4 COMMENT='咨询索引';
CREATE TABLE `sky_mod_sjsj_guestindex` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `createtime` datetime NOT NULL DEFAULT '2019-02-23 05:31:01' COMMENT '创建时间',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `touserid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户2',
  `objectid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类信息',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `objectid_userid` (`objectid`,`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COMMENT='咨询索引';
CREATE TABLE `sky_mod_sjsj_news` (
  `newsid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '标题',
  `createtime` datetime NOT NULL DEFAULT '2022-06-05 06:30:31' COMMENT '创建时间',
  `updatetime` datetime NOT NULL DEFAULT '2022-06-05 06:30:31' COMMENT '更新时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `content` varchar(225) NOT NULL DEFAULT '' COMMENT '内容',
  `sj_content` varchar(225) NOT NULL,
  `sj_money` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '赏金',
  `isbuy` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `buyer` int(10) unsigned NOT NULL DEFAULT '0',
  `issuccess` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`newsid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='商机信息';
CREATE TABLE `sky_mod_sjsj_news_follow` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `newsid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `newsid` (`newsid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户订阅';
CREATE TABLE `sky_mod_sjsj_news_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `newsid` int(10) unsigned NOT NULL DEFAULT '0',
  `tagid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `newsid` (`newsid`),
  KEY `tagid` (`tagid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商机标签';
CREATE TABLE `sky_mod_sjsj_tags` (
  `tagid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '推荐',
  `createtime` datetime NOT NULL DEFAULT '2022-06-09 22:14:15',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`tagid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='标签';
CREATE TABLE `sky_mod_sjsj_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `createtime` datetime NOT NULL DEFAULT '2022-06-05 06:30:31' COMMENT '创建时间',
  `updatetime` datetime NOT NULL DEFAULT '2022-06-05 06:30:31' COMMENT '更新时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `money` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '余额',
  `income` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收入',
  `post_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发布数',
  `post_sold_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '买断数',
  `post_success_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '赏金数',
  `post_fail_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '失效数',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='商机-用户';
CREATE TABLE `sky_mod_sjsj_user_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tagid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tagid` (`tagid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='用户标签';
CREATE TABLE `sky_mod_sjsj_usermoney_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `money` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '金额',
  `content` varchar(225) NOT NULL DEFAULT '' COMMENT '内容',
  `createtime` datetime NOT NULL DEFAULT '2022-06-09 22:14:15',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='金额日志';

eof;
?>