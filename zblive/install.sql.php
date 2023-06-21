<?php
$content=<<<eof
CREATE TABLE `sky_mod_zblive` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `auth_key` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `fav_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数',
  `gift_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '礼物数',
  `total_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '总收入',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0',
  `grade` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `vdsize` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '尺寸',
  `description` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `gonggao` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '/static/images/zbimgurl.jpg',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '推荐',
  `isnew` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '最新',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  `mp4url` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `zbstatus` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isindex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '首页显示',
  `city` varchar(32) NOT NULL DEFAULT '',
  `is_recommend` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '推荐',
  `ishot` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '最热',
  `isback` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `tablename` varchar(255) NOT NULL DEFAULT '' COMMENT '产品类型',
  `proids` varchar(255) NOT NULL DEFAULT '' COMMENT '产品id',
  `offtime` int(10) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext CHARACTER SET utf8,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COMMENT='房间';
CREATE TABLE `sky_mod_zblive_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `zbrtmp` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `zbvhost` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `zbrtmp_key` varchar(32) NOT NULL DEFAULT '',
  `zbpath` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `zbkey` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `zbappname` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `backhost` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `AccessKeyId` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `AccessKeyKey` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `wshost` varchar(225) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='设置';
CREATE TABLE `sky_mod_zblive_hoster` (
  `hostid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`hostid`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='主播';
CREATE TABLE `sky_mod_zblive_hoster_apply` (
  `hostid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) NOT NULL DEFAULT '',
  PRIMARY KEY (`hostid`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COMMENT='主播申请';
CREATE TABLE `sky_mod_zblive_liveaccess` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='直播权限';
CREATE TABLE `sky_mod_zblive_msg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `room_id` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `to_userid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text,
  PRIMARY KEY (`id`),
  KEY `room_id` (`room_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=168 DEFAULT CHARSET=utf8mb4 COMMENT='聊天记录';
CREATE TABLE `sky_mod_zblive_product` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `productid` int(10) unsigned NOT NULL DEFAULT '0',
  `tablename` varchar(32) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `room_id` int(10) unsigned NOT NULL DEFAULT '0',
  `orderindex` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `room_id` (`room_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='直播产品';

eof;
?>