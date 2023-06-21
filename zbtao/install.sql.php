<?php
$content=<<<eof
CREATE TABLE `sky_mod_zbtao_live` (
  `liveid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ppid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(225) NOT NULL DEFAULT '' COMMENT '描述',
  `zbtime` datetime NOT NULL DEFAULT '2021-12-29 01:02:03' COMMENT '直播时间',
  `zbpts` varchar(225) NOT NULL DEFAULT '' COMMENT '直播平台',
  `createtime` datetime NOT NULL DEFAULT '2021-12-29 01:02:03' COMMENT '创建时间',
  `grade` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `raty_grade` decimal(3,1) NOT NULL DEFAULT '0.0' COMMENT '评价',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '头像',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `followed_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关注数',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '喜欢数',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '介绍',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '推荐',
  PRIMARY KEY (`liveid`),
  KEY `ppid` (`ppid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='直播';
CREATE TABLE `sky_mod_zbtao_live_follow` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `liveid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `liveid` (`liveid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='直播关注';
CREATE TABLE `sky_mod_zbtao_live_product` (
  `productid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `liveid` int(10) unsigned NOT NULL DEFAULT '0',
  `ppid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(225) NOT NULL DEFAULT '' COMMENT '描述',
  `zbtime` datetime NOT NULL DEFAULT '2021-12-29 01:02:03' COMMENT '直播时间',
  `createtime` datetime NOT NULL DEFAULT '2021-12-29 01:02:03' COMMENT '创建时间',
  `grade` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `raty_grade` decimal(3,1) NOT NULL DEFAULT '0.0' COMMENT '评价',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '头像',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `followed_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关注数',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '喜欢数',
  `price` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `market_price` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '原价',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '推荐',
  PRIMARY KEY (`productid`),
  KEY `liveid` (`liveid`),
  KEY `ppid` (`ppid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='直播商品';
CREATE TABLE `sky_mod_zbtao_live_product_follow` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `productid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `productid` (`productid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='商品关注';
CREATE TABLE `sky_mod_zbtao_live_product_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tagid` int(10) unsigned NOT NULL DEFAULT '0',
  `productid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tagid` (`tagid`),
  KEY `productid` (`productid`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='产品标签';
CREATE TABLE `sky_mod_zbtao_live_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tagid` int(10) unsigned NOT NULL DEFAULT '0',
  `liveid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tagid` (`tagid`),
  KEY `liveid` (`liveid`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='直播标签';
CREATE TABLE `sky_mod_zbtao_pp` (
  `ppid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nickname` varchar(32) NOT NULL DEFAULT '' COMMENT '昵称',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '描述',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '推荐',
  `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别',
  `createtime` datetime NOT NULL DEFAULT '2021-12-29 01:02:03' COMMENT '创建时间',
  `grade` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `raty_grade` decimal(3,1) NOT NULL DEFAULT '0.0' COMMENT '评价',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '头像',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `followed_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关注数',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '喜欢数',
  PRIMARY KEY (`ppid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='主播';
CREATE TABLE `sky_mod_zbtao_pp_apply` (
  `ppid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nickname` varchar(32) NOT NULL DEFAULT '' COMMENT '昵称',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '描述',
  `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别',
  `createtime` datetime NOT NULL DEFAULT '2021-12-29 01:02:03' COMMENT '创建时间',
  `grade` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `raty_grade` decimal(3,1) NOT NULL DEFAULT '0.0' COMMENT '评价',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '头像',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `followed_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关注数',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '喜欢数',
  PRIMARY KEY (`ppid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='主播申请入驻';
CREATE TABLE `sky_mod_zbtao_pp_follow` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ppid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `ppid` (`ppid`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='主播关注';
CREATE TABLE `sky_mod_zbtao_pp_pts` (
  `ptid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ppid` int(10) unsigned NOT NULL DEFAULT '0',
  `nickname` varchar(32) NOT NULL DEFAULT '' COMMENT '主播名称',
  `zbhao` varchar(32) NOT NULL DEFAULT '' COMMENT '主播ID',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '描述',
  `createtime` datetime NOT NULL DEFAULT '2021-12-29 01:02:03' COMMENT '创建时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `ptcom` varchar(12) NOT NULL DEFAULT '' COMMENT '所属平台',
  PRIMARY KEY (`ptid`),
  KEY `ppid` (`ppid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='主播相关平台';
CREATE TABLE `sky_mod_zbtao_pp_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tagid` int(10) unsigned NOT NULL DEFAULT '0',
  `ppid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tagid` (`tagid`),
  KEY `ppid` (`ppid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='主播标签';
CREATE TABLE `sky_mod_zbtao_tag` (
  `tagid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `orderindex` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`tagid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='标签';
CREATE TABLE `sky_mod_zbtao_user_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tagid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tagid` (`tagid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='用户标签';

eof;
?>