<?php
$content=<<<eof
CREATE TABLE `sky_mod_house_agent` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` varchar(16) NOT NULL DEFAULT '',
  `truename` varchar(16) NOT NULL DEFAULT '' COMMENT '姓名',
  `userno` varchar(32) NOT NULL DEFAULT '' COMMENT '身份证',
  `uhead` varchar(225) NOT NULL DEFAULT '' COMMENT '头像',
  `telephone` varchar(18) NOT NULL DEFAULT '' COMMENT '电话',
  `description` varchar(128) NOT NULL DEFAULT '' COMMENT '简介',
  `wxhao` varchar(32) NOT NULL DEFAULT '' COMMENT '微信号',
  `wxemw` varchar(225) NOT NULL DEFAULT '' COMMENT '二维码',
  `usercard` varchar(225) NOT NULL DEFAULT '' COMMENT '身份证',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_house_agent_apply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` varchar(16) NOT NULL DEFAULT '',
  `truename` varchar(16) NOT NULL DEFAULT '' COMMENT '姓名',
  `userno` varchar(32) NOT NULL DEFAULT '' COMMENT '身份证',
  `uhead` varchar(225) NOT NULL DEFAULT '' COMMENT '头像',
  `telephone` varchar(18) NOT NULL DEFAULT '' COMMENT '电话',
  `description` varchar(128) NOT NULL DEFAULT '' COMMENT '简介',
  `wxhao` varchar(32) NOT NULL DEFAULT '' COMMENT '微信号',
  `wxemw` varchar(225) NOT NULL DEFAULT '' COMMENT '二维码',
  `usercard` varchar(225) NOT NULL DEFAULT '' COMMENT '身份证',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_house_huxing` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lpid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '楼盘',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `description` varchar(32) NOT NULL DEFAULT '' COMMENT '面积',
  `total_money` int(11) NOT NULL DEFAULT '0' COMMENT '总价',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(255) NOT NULL DEFAULT '',
  `imgsdata` text NOT NULL COMMENT '图集',
  PRIMARY KEY (`id`),
  KEY `lpid` (`lpid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='户型';
CREATE TABLE `sky_mod_house_loupan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '简介',
  `isbuy` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '在售',
  `price` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '价格',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '图片',
  `videourl` varchar(225) NOT NULL DEFAULT '',
  `address` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0',
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `kfs` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '开发商',
  `tel_400` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '400电话',
  `tel_400_z` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '转号',
  `kp_time` date NOT NULL DEFAULT '2017-10-12' COMMENT '开盘时间',
  `createtime` datetime NOT NULL DEFAULT '2017-10-12 07:15:01',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_house_loupan_love` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lpid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `lpid` (`lpid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_house_peitao` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lpid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '楼盘',
  `typeid` tinyint(4) NOT NULL DEFAULT '0' COMMENT '类型',
  `content` varchar(225) NOT NULL DEFAULT '' COMMENT '简介',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `lpid` (`lpid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='peitao';
CREATE TABLE `sky_mod_house_resource` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '简介',
  `isnew` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '1新房 0二手房',
  `price` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '价格',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `address` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `huxing` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '户型',
  `sc_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '位置',
  `mianji` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '面积',
  `danjia` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '单价',
  `total_money` decimal(15,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '总价',
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '电话',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2017-10-12 07:15:01',
  `updatetime` datetime NOT NULL DEFAULT '2017-10-12 07:15:01',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0',
  `imgsdata` text CHARACTER SET utf8,
  `content` mediumtext CHARACTER SET utf8,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_house_resource_love` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `resid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `resid` (`resid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_house_stole` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `isstole` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `url` varchar(225) CHARACTER SET utf8 NOT NULL,
  `content` mediumtext CHARACTER SET utf8,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1771 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_house_tags` (
  `tagid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `total_num` int(10) unsigned NOT NULL DEFAULT '0',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0',
  `gkey` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `gnum` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`tagid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_house_tags_index` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tagid` int(10) unsigned NOT NULL DEFAULT '0',
  `objectid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderindex` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tagid` (`tagid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_house_tuan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '简介',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) DEFAULT '',
  `address` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `truename` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '联系人',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '电话',
  `wxhao` varchar(32) NOT NULL DEFAULT '' COMMENT '微信号',
  `wxewm` varchar(225) NOT NULL DEFAULT '' COMMENT '二维码',
  `join_num` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2019-12-12 07:15:01',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0',
  `stime` datetime NOT NULL DEFAULT '2019-12-12 07:15:01',
  `etime` datetime NOT NULL DEFAULT '2019-12-12 07:15:01',
  `max_num` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '限制人数',
  `hongbao` smallint(5) unsigned NOT NULL DEFAULT '0',
  `invite_hongbao` smallint(5) unsigned DEFAULT '0' COMMENT '邀请红包',
  `content` text CHARACTER SET utf8,
  `videourl` varchar(225) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_house_tuan_raty` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `raty_grade` tinyint(3) unsigned NOT NULL DEFAULT '8',
  `raty_content` varchar(225) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_house_tuan_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `telephone` varchar(18) NOT NULL DEFAULT '',
  `truename` varchar(12) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `israty` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ischeck` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ishongbao` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `raty_grade` tinyint(3) unsigned NOT NULL DEFAULT '8',
  `invite_userid` int(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='看房团报名';

eof;
?>