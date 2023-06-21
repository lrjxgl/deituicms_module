<?php
$content=<<<eof
CREATE TABLE `sky_mod_exam` (
  `exid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '题目',
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `createtime` datetime NOT NULL DEFAULT '2019-12-04 12:16:17' COMMENT '创建时间',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '简介',
  `userid` int(10) unsigned NOT NULL DEFAULT '1',
  `isonline` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `isone` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `extime` int(10) unsigned NOT NULL DEFAULT '0',
  `tpl` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`exid`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COMMENT='考卷';
CREATE TABLE `sky_mod_exam_answer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `exid` int(10) unsigned NOT NULL DEFAULT '0',
  `exuserid` int(11) NOT NULL DEFAULT '0' COMMENT '出卷人',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `grade` decimal(7,2) NOT NULL DEFAULT '0.00',
  `createtime` datetime NOT NULL DEFAULT '2019-04-12 07:57:01',
  `israty` tinyint(4) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext CHARACTER SET utf8,
  `ratycontent` text CHARACTER SET utf8,
  PRIMARY KEY (`id`),
  KEY `exid` (`exid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COMMENT='考卷-试题';
CREATE TABLE `sky_mod_exam_ask` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `topicid` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '题目',
  `exid` int(10) unsigned NOT NULL DEFAULT '0',
  `grade` decimal(7,2) NOT NULL DEFAULT '0.00',
  `orderindex` tinyint(4) NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `exid` (`exid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COMMENT='考卷-试题';
CREATE TABLE `sky_mod_exam_topic` (
  `topicid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '题目',
  `typeid` varchar(12) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `userid` int(11) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `createtime` datetime NOT NULL DEFAULT '2019-12-04 12:16:17' COMMENT '创建时间',
  `answer` text CHARACTER SET utf8 COMMENT '答案',
  `content` mediumtext CHARACTER SET utf8 COMMENT '内容',
  `jsondata` mediumtext CHARACTER SET utf8,
  PRIMARY KEY (`topicid`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COMMENT='试卷题目';

eof;
?>