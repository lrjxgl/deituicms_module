<?php
$content=<<<eof
CREATE TABLE `sky_mod_jieti_answer` (
  `answerid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `createtime` datetime NOT NULL DEFAULT '2018-12-03 01:01:01',
  `typeid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '年级',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '图片',
  `content` varchar(225) NOT NULL DEFAULT '' COMMENT '内容',
  `mp3url` varchar(225) NOT NULL DEFAULT '',
  `mp4url` varchar(225) NOT NULL DEFAULT '',
  PRIMARY KEY (`answerid`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_jieti_ask` (
  `askid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `createtime` datetime NOT NULL DEFAULT '2018-12-03 01:01:01',
  `catid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '年级',
  `catid_2nd` int(10) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '图片',
  `content` varchar(225) NOT NULL DEFAULT '' COMMENT '内容',
  `isanswer` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否回答',
  `answerid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '答案',
  `money` decimal(9,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '金额',
  `typeid` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '服务方式 0普通 1加急',
  `answer_time` datetime NOT NULL DEFAULT '2018-12-03 01:01:01' COMMENT '回答时间',
  `answer_userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '答题用户',
  `ispay` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否支付 0未支付 1已支付',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`askid`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_jieti_category` (
  `catid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '描述',
  `orderindex` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) NOT NULL DEFAULT '',
  PRIMARY KEY (`catid`),
  KEY `pid` (`pid`,`orderindex`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

eof;
?>