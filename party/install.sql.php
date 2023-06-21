<?php
$content=<<<eof
CREATE TABLE `sky_mod_party` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `tablename` varchar(32) NOT NULL DEFAULT '' COMMENT '来源应用',
  `objectid` int(10) unsigned NOT NULL DEFAULT '0',
  `nickname` varchar(32) NOT NULL DEFAULT '' COMMENT '联系人',
  `telephone` varchar(32) NOT NULL DEFAULT '' COMMENT '电话',
  `address` varchar(32) NOT NULL DEFAULT '' COMMENT '地址',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT 'logo',
  `choice_gender` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '开启性别比例',
  `createtime` datetime NOT NULL DEFAULT '2018-07-26 07:16:01',
  `stime` datetime NOT NULL DEFAULT '2021-06-25 14:43:01' COMMENT '活动开始时间',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '描述',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `max_num` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '限制人数',
  `retype` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付方式 0线下 1线上',
  `money` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `join_num` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '参与人数',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000' COMMENT '纬度',
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000' COMMENT '精度',
  `isfinish` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` text,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `tablename_objectid` (`tablename`,`objectid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_party_blog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `partyid` int(10) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_num` int(10) unsigned NOT NULL DEFAULT '0',
  `mp4url` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `createtime` datetime NOT NULL DEFAULT '2018-04-10 09:12:01',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `lat` decimal(9,6) unsigned NOT NULL DEFAULT '0.000000',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `imgsdata` text CHARACTER SET utf8 COMMENT '图集',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_party_blog_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `partyid` int(10) unsigned NOT NULL DEFAULT '0',
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_party_blog_view` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `partyid` int(10) unsigned NOT NULL DEFAULT '0',
  `blogid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2019-01-09 12:20:21',
  PRIMARY KEY (`id`),
  KEY `blogid` (`blogid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_party_join` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `nickname` varchar(32) NOT NULL DEFAULT '' COMMENT '联系人',
  `telephone` varchar(32) NOT NULL DEFAULT '' COMMENT '电话',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `tablename` varchar(15) NOT NULL DEFAULT '',
  `createtime` datetime NOT NULL DEFAULT '2018-07-26 07:16:01',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `ispay` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否支付 1已支付 0未支付',
  `money` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '自我介绍',
  `checkin` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '签到 1是 0否',
  `recharge_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

eof;
?>