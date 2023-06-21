<?php
$content=<<<eof
CREATE TABLE `sky_mod_fishing_blog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `placeid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) NOT NULL DEFAULT '',
  `imgsdata` varchar(512) NOT NULL DEFAULT '',
  `createtime` datetime NOT NULL DEFAULT '2021-11-16 01:02:03',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_num` int(10) unsigned NOT NULL DEFAULT '0',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `mp4url` varchar(225) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `placeid` (`placeid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COMMENT='钓点动态';
CREATE TABLE `sky_mod_fishing_blog_comment` (
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
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_fishing_blog_view` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `objectid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2019-01-09 12:20:21',
  PRIMARY KEY (`id`),
  KEY `objectid` (`objectid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=799 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_fishing_checkin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `placeid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) NOT NULL DEFAULT '',
  `imgsdata` varchar(512) NOT NULL DEFAULT '',
  `createtime` datetime NOT NULL DEFAULT '2021-11-16 01:02:03',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `placeid` (`placeid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COMMENT='打卡';
CREATE TABLE `sky_mod_fishing_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `checkin_post_grade` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '打卡积分',
  `blog_post_grade` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '发博积分',
  `tag_post_grade` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '渔获积分',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='钓鱼配置';
CREATE TABLE `sky_mod_fishing_fav` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `placeid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`,`placeid`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='收藏';
CREATE TABLE `sky_mod_fishing_free_account` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `placeid` int(10) unsigned NOT NULL DEFAULT '0',
  `money` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '余额',
  `income` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '收入',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2021-11-09 01:02:03' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `placeid` (`placeid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='放流位置';
CREATE TABLE `sky_mod_fishing_free_account_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `placeid` int(10) unsigned NOT NULL DEFAULT '0',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `createtime` datetime NOT NULL DEFAULT '2022-08-17 07:15:01',
  `content` varchar(225) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `userid` (`placeid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_fishing_free_activity` (
  `actid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `placeid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(64) NOT NULL DEFAULT '',
  `description` varchar(225) NOT NULL DEFAULT '',
  `money` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `stime` datetime NOT NULL DEFAULT '2021-11-09 01:02:03' COMMENT '活动时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2021-11-09 01:02:03' COMMENT '创建时间',
  `imgurl` varchar(128) NOT NULL DEFAULT '',
  `isfinish` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text,
  PRIMARY KEY (`actid`),
  KEY `placeid` (`placeid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='放流活动';
CREATE TABLE `sky_mod_fishing_free_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `placeid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2022-08-17 07:15:01',
  `nickname` varchar(12) NOT NULL DEFAULT '',
  `user_head` varchar(128) NOT NULL DEFAULT '/static/images/user_head.jpg',
  `telephone` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `placeid` (`placeid`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_fishing_free_join` (
  `joinid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `placeid` int(10) unsigned NOT NULL DEFAULT '0',
  `actid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `nickname` varchar(12) NOT NULL DEFAULT '' COMMENT '联系人',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '电话',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2022-08-17 07:15:01',
  `ischeck` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`joinid`),
  KEY `actid` (`actid`),
  KEY `placeid` (`placeid`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_fishing_free_order` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `placeid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `money` decimal(9,2) NOT NULL DEFAULT '0.00',
  `description` varchar(225) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2021-11-09 01:02:03' COMMENT '创建时间',
  `ispay` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `recharge_id` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`orderid`),
  KEY `placeid` (`placeid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='放流位置';
CREATE TABLE `sky_mod_fishing_free_place` (
  `placeid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '描述',
  `fishing` varchar(225) NOT NULL DEFAULT '' COMMENT '可放鱼类',
  `address` varchar(225) NOT NULL DEFAULT '' COMMENT '地址',
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2021-11-09 01:02:03' COMMENT '创建时间',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '访问人数',
  `join_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '打卡人数',
  `imgsdata` varchar(512) NOT NULL DEFAULT '' COMMENT '图集',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '主图',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text,
  PRIMARY KEY (`placeid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='放流位置';
CREATE TABLE `sky_mod_fishing_place` (
  `placeid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '描述',
  `address` varchar(225) NOT NULL DEFAULT '' COMMENT '地址',
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2021-11-09 01:02:03' COMMENT '创建时间',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '访问人数',
  `checkin_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '打卡人数',
  `topic_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '主题数',
  `imgsdata` varchar(512) NOT NULL DEFAULT '' COMMENT '图集',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '主图',
  `is_allow` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否可钓 0允许 1警告 2禁止',
  `tags` varchar(225) NOT NULL DEFAULT '',
  `grade` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`placeid`),
  KEY `shopid` (`shopid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='钓鱼地点';
CREATE TABLE `sky_mod_fishing_place_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `placeid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(15) NOT NULL DEFAULT '' COMMENT '名称',
  `createtime` datetime NOT NULL DEFAULT '2021-11-16 21:56:01',
  `grade` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `placeid` (`placeid`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='钓点标签';
CREATE TABLE `sky_mod_fishing_place_utag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `placeid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) NOT NULL DEFAULT '' COMMENT '标签内容',
  `createtime` datetime NOT NULL DEFAULT '2021-11-16 21:56:01',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `placeid` (`placeid`,`userid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='钓点用户标签';
CREATE TABLE `sky_mod_fishing_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(15) NOT NULL DEFAULT '' COMMENT '名称',
  `createtime` datetime NOT NULL DEFAULT '2021-11-16 21:56:01',
  `grade` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='标签';

eof;
?>