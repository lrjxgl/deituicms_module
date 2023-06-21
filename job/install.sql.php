<?php
$content=<<<eof
CREATE TABLE `sky_mod_job` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `jz_money` decimal(9,2) unsigned NOT NULL DEFAULT '1.00' COMMENT '兼职付费',
  `qz_money` decimal(9,2) unsigned NOT NULL DEFAULT '1.00' COMMENT '全职付费',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_job_category` (
  `catid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderindex` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `tablename` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_job_company` (
  `comid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '标题',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `nickname` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系人',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系电话',
  `address` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态 1上线 ',
  `content` text CHARACTER SET utf8 COMMENT '详情',
  `createtime` datetime NOT NULL DEFAULT '2019-01-03 19:51:01',
  PRIMARY KEY (`comid`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_job_jianli` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `nickname` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名字',
  `age` smallint(5) unsigned NOT NULL DEFAULT '2019' COMMENT '年龄',
  `gender` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '性别 1男性 2女性',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '自我介绍',
  `job` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '工作经历',
  `xueli` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '学历',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '照片',
  `createtime` datetime NOT NULL DEFAULT '2019-01-03 19:51:01',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系电话',
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_job_jianzhi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '标题',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `nickname` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系人',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系电话',
  `money` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '工资',
  `address` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态 1上线 ',
  `ispay` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付 1已支付',
  `paymoney` decimal(9,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '支付金额',
  `comid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公司',
  `content` text CHARACTER SET utf8 COMMENT '详情',
  `catid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类',
  `startTime` datetime NOT NULL DEFAULT '2019-01-02 13:01:35',
  `isrecommend` tinyint(4) NOT NULL DEFAULT '0' COMMENT '推荐',
  `createtime` datetime NOT NULL DEFAULT '2019-01-02 13:01:35',
  `num` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '人数',
  `view_num` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '访问人数',
  `bm_num` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '报名人数',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `comid` (`comid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_job_jianzhi_baoming` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `objectid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '兼职',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `nickname` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '昵称',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '手机',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '简介',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `createtime` datetime NOT NULL DEFAULT '2019-01-03 11:42:01',
  PRIMARY KEY (`id`),
  KEY `objectid` (`objectid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_job_quanzhi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '标题',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `nickname` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系人',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系电话',
  `money` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '工资',
  `address` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态 1上线 ',
  `ispay` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付 1已支付',
  `paymoney` decimal(9,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '支付金额',
  `comid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公司',
  `content` text CHARACTER SET utf8 COMMENT '详情',
  `catid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类',
  `isrecommend` tinyint(4) NOT NULL DEFAULT '0' COMMENT '推荐',
  `createtime` datetime NOT NULL DEFAULT '2019-01-02 13:01:35',
  `num` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '人数',
  `view_num` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '访问人数',
  `bm_num` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '报名人数',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `comid` (`comid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_job_quanzhi_baoming` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `objectid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '全职',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `nickname` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '昵称',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '手机',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '简介',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `createtime` datetime NOT NULL DEFAULT '2019-01-03 11:42:01',
  PRIMARY KEY (`id`),
  KEY `objectid` (`objectid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

eof;
?>