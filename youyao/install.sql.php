<?php
$content=<<<eof
CREATE TABLE `sky_mod_youyao_find` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) NOT NULL DEFAULT '',
  `createtime` datetime NOT NULL DEFAULT '2022-12-24 15:01:01',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `lng` decimal(9,6) unsigned NOT NULL DEFAULT '0.000000',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='求药';
CREATE TABLE `sky_mod_youyao_guest` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `createtime` datetime NOT NULL DEFAULT '2019-02-23 05:31:01' COMMENT '创建时间',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户2',
  `productid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类信息',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `author` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `ukey` varchar(12) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `objectid_userid` (`productid`,`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_youyao_guestindex` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `createtime` datetime NOT NULL DEFAULT '2019-02-23 05:31:01' COMMENT '创建时间',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户2',
  `productid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类信息',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `author` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `ukey` varchar(12) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `objectid_userid` (`productid`,`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_youyao_product` (
  `productid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `catid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '类别',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `createtime` datetime NOT NULL DEFAULT '2022-10-31 21:01:02' COMMENT '发布时间',
  `updatetime` datetime NOT NULL DEFAULT '2022-10-31 21:01:02' COMMENT '更新时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `sitecheck` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '站点审核',
  `price` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `market_price` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '市场价格',
  `imgsdata` varchar(512) NOT NULL DEFAULT '' COMMENT '图集',
  `imgurl` varchar(128) NOT NULL DEFAULT '' COMMENT '主图',
  `description` varchar(256) NOT NULL DEFAULT '' COMMENT '描述',
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000' COMMENT '维度',
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000' COMMENT '精度',
  `address` varchar(64) NOT NULL DEFAULT '' COMMENT '地址',
  `cityid` int(10) unsigned NOT NULL DEFAULT '0',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '想要',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '浏览',
  `sold_num` int(10) unsigned NOT NULL DEFAULT '0',
  `fav_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏',
  `comment_num` int(10) unsigned NOT NULL DEFAULT '0',
  `shoptype` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `baoyou` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `total_num` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`productid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='产品';
CREATE TABLE `sky_mod_youyao_shop` (
  `shopid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopname` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000' COMMENT '纬度',
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000' COMMENT '经度',
  `nickname` varchar(12) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系电话',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `isrecommend` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `product_num` int(10) unsigned NOT NULL DEFAULT '0',
  `sold_num` int(10) unsigned NOT NULL DEFAULT '0',
  `grade` decimal(2,1) unsigned NOT NULL DEFAULT '9.9',
  `address` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `follow_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关注数',
  `imgurl` varchar(225) NOT NULL DEFAULT '/static/images/no_image.jpg',
  PRIMARY KEY (`shopid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COMMENT='商家';
CREATE TABLE `sky_mod_youyao_shop_apply` (
  `shopid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopname` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `address` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000' COMMENT '纬度',
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000' COMMENT '经度',
  `nickname` varchar(12) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系电话',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`shopid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='商家申请入住';

eof;
?>