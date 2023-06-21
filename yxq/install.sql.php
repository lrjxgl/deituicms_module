<?php
$content=<<<eof
CREATE TABLE `sky_mod_yxq_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_money` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `get_money` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `invite_per` decimal(5,2) unsigned NOT NULL DEFAULT '0.10',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='配置';
CREATE TABLE `sky_mod_yxq_paper` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `gender` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2022-05-04 08:01:01',
  `updatetime` datetime NOT NULL DEFAULT '2022-05-04 08:01:01',
  `ispay` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `isfinish` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `user_num` int(10) unsigned NOT NULL DEFAULT '0',
  `money` decimal(9,2) NOT NULL DEFAULT '0.00',
  `grade` tinyint(4) NOT NULL DEFAULT '0',
  `wxhao` varchar(32) NOT NULL DEFAULT '',
  `xingzuo` varchar(32) NOT NULL DEFAULT '',
  `description` varchar(225) NOT NULL DEFAULT '',
  `imgsdata` varchar(512) NOT NULL DEFAULT '',
  `imgurl` varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='纸条';
CREATE TABLE `sky_mod_yxq_paper_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `ppid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='我接收的';

eof;
?>