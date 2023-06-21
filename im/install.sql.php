<?php
$content=<<<eof
CREATE TABLE `sky_mod_im_ai` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '名称',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `apiname` varchar(16) NOT NULL DEFAULT '' COMMENT 'api名称',
  `appid` varchar(64) NOT NULL DEFAULT '' COMMENT 'appid',
  `appkey` varchar(64) NOT NULL DEFAULT '' COMMENT 'appkey',
  `content` text COMMENT '配置内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_im_bored` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `nickname` varchar(15) NOT NULL DEFAULT '',
  `user_head` varchar(225) NOT NULL DEFAULT '',
  `description` varchar(225) NOT NULL DEFAULT '',
  `city` varchar(16) NOT NULL DEFAULT '',
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `age` smallint(5) unsigned NOT NULL DEFAULT '0',
  `gender` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `dateline` (`dateline`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1060 DEFAULT CHARSET=utf8mb4 COMMENT='无聊的人';
CREATE TABLE `sky_mod_im_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `wsHost` varchar(32) NOT NULL DEFAULT '' COMMENT '域名',
  `aiusers` text COMMENT 'ai用户配置',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_im_gift` (
  `giftid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '类别',
  `title` varchar(22) NOT NULL DEFAULT '' COMMENT '名称',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '介绍',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '图片地址',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '推荐',
  `price` int(11) NOT NULL DEFAULT '0' COMMENT '价格',
  PRIMARY KEY (`giftid`),
  KEY `catid` (`catid`,`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='礼物';
CREATE TABLE `sky_mod_im_gift_send` (
  `sendid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `giftid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '礼物',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '类别',
  `touserid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '类别',
  `content` varchar(225) NOT NULL DEFAULT '' COMMENT '留言',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `isread` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '阅读',
  PRIMARY KEY (`sendid`),
  KEY `userid` (`userid`,`isread`) USING BTREE,
  KEY `touserid` (`touserid`,`isread`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=370 DEFAULT CHARSET=utf8mb4 COMMENT='礼物仓库';
CREATE TABLE `sky_mod_im_gold` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gold` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '金币',
  `price` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '价格',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='im金币商城';
CREATE TABLE `sky_mod_im_group` (
  `groupid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `catid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '描述',
  `createtime` datetime NOT NULL DEFAULT '2019-07-13 11:28:01',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT 'logo',
  `orderindex` int(10) unsigned NOT NULL DEFAULT '0',
  `typeid` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `need_join` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '需加入',
  `user_num` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`groupid`),
  KEY `catid` (`catid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='im群组';
CREATE TABLE `sky_mod_im_group_msg` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `groupid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `touserid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `isread` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) NOT NULL DEFAULT '' COMMENT '内容',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `ut` (`userid`,`touserid`,`dateline`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1861 DEFAULT CHARSET=utf8mb4 COMMENT='群消息';
CREATE TABLE `sky_mod_im_group_msg_index` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `groupid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) NOT NULL DEFAULT '' COMMENT '内容',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `groupid` (`groupid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1861 DEFAULT CHARSET=utf8mb4 COMMENT='群消息索引';
CREATE TABLE `sky_mod_im_group_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `groupid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_group` (`userid`,`groupid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_im_group_user_apply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `groupid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `user_group` (`userid`,`groupid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_im_match` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `gender` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `age` smallint(5) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `stype` varchar(12) NOT NULL DEFAULT '',
  `user_head` varchar(225) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `stype` (`stype`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1059 DEFAULT CHARSET=utf8 COMMENT='匹配交友';
CREATE TABLE `sky_mod_im_match_success` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `touserid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `stype` varchar(12) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `stype` (`stype`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1055 DEFAULT CHARSET=utf8 COMMENT='匹配交友-成功';
CREATE TABLE `sky_mod_im_msg` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `touserid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `isread` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) NOT NULL DEFAULT '' COMMENT '内容',
  `isme` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `ut` (`userid`,`touserid`,`dateline`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6100 DEFAULT CHARSET=utf8mb4 COMMENT='消息';
CREATE TABLE `sky_mod_im_msg_index` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `touserid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `isread` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `num` smallint(5) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) NOT NULL DEFAULT '' COMMENT '内容',
  PRIMARY KEY (`id`),
  KEY `ut` (`userid`,`touserid`,`dateline`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6097 DEFAULT CHARSET=utf8mb4 COMMENT='消息';

eof;
?>