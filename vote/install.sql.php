<?php
$content=<<<eof
CREATE TABLE `sky_mod_vote` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `title` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '标题',
  `imgurl` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `is_temp` tinyint(4) NOT NULL DEFAULT '0' COMMENT '临时文件',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `award` text CHARACTER SET utf8 COMMENT '奖品',
  `ruledesc` text CHARACTER SET utf8 COMMENT '参与规则',
  `join_num` int(11) NOT NULL DEFAULT '0',
  `view_num` int(11) NOT NULL DEFAULT '0',
  `vote_num` int(11) NOT NULL DEFAULT '0',
  `gotype` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '投票类型',
  `gonum` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '投票次数',
  `typeid` tinyint(4) NOT NULL DEFAULT '1' COMMENT '类型 1图片 2文章 3音乐 4视频',
  `tpl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `shopid_status` (`shopid`,`status`) USING BTREE,
  KEY `shopappid_status` (`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COMMENT='主表';
CREATE TABLE `sky_mod_vote_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext CHARACTER SET utf8 COMMENT '内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COMMENT='附表';
CREATE TABLE `sky_mod_vote_go` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `vid` int(10) unsigned NOT NULL DEFAULT '0',
  `joinid` int(10) unsigned NOT NULL DEFAULT '0',
  `time` datetime NOT NULL DEFAULT '2016-01-01 10:22:22',
  `ip` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `ipname` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `vid` (`vid`) USING BTREE,
  KEY `joinid` (`joinid`) USING BTREE,
  KEY `userid` (`userid`,`vid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COMMENT='投票支持表';
CREATE TABLE `sky_mod_vote_join` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',
  `nickname` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '电话',
  `address` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '图片',
  `url` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '网址',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `istemp` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间',
  `isrecommend` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `vote_num` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '标题',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `comment_num` int(10) unsigned NOT NULL DEFAULT '0',
  `bianhao` int(11) unsigned NOT NULL DEFAULT '0',
  `content` text CHARACTER SET utf8,
  `imgsdata` text CHARACTER SET utf8,
  PRIMARY KEY (`id`),
  KEY `vid` (`vid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COMMENT='参与用户';
CREATE TABLE `sky_mod_vote_join_comment` (
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
) ENGINE=InnoDB AUTO_INCREMENT=192 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_vote_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `time` datetime NOT NULL DEFAULT '2016-01-01 10:22:22',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户';

eof;
?>