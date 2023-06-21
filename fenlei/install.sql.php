<?php
$content=<<<eof
CREATE TABLE `sky_mod_fenlei` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '标题',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '图片',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `nickname` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系人',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系电话',
  `money` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '工资',
  `address` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态 1上线 ',
  `ispay` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付 1已支付',
  `paymoney` decimal(9,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '支付金额',
  `catid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类',
  `startTime` datetime NOT NULL DEFAULT '2019-01-02 13:01:35',
  `isindex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '首页推荐',
  `isindex_etime` int(10) unsigned DEFAULT '0' COMMENT '首页置顶结束时间',
  `catding` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '分类置顶',
  `catding_etime` int(10) unsigned NOT NULL DEFAULT '0',
  `isrecommend` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '推荐',
  `createtime` datetime NOT NULL DEFAULT '2019-01-02 13:01:35',
  `num` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '人数',
  `view_num` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '访问人数',
  `bm_num` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '报名人数',
  `comment_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  `sc_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所在区域',
  `love_num` int(10) unsigned NOT NULL,
  `fav_num` int(10) unsigned NOT NULL DEFAULT '0',
  `typeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '类型',
  `updatetime` datetime NOT NULL DEFAULT '2019-01-02 13:01:35',
  `hb_money` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '红包金额',
  `hb_on` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '开启红包',
  `offtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下架时间',
  `hb_num` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '红包数量',
  `ex_table_data_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '扩展表id',
  `content` text CHARACTER SET utf8 COMMENT '详情',
  `imgsdata` text CHARACTER SET utf8 COMMENT '图集',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_fenlei_attr` (
  `id` int(10) unsigned NOT NULL,
  `content` mediumtext CHARACTER SET utf8,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_fenlei_black` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) NOT NULL DEFAULT '',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_fenlei_category` (
  `catid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderindex` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `money` decimal(9,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '费用',
  `dingmoney` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '置顶费用',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `type_open` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `typedata` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '类型设置',
  `typeLabel` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '类型' COMMENT '类型别名',
  `moneyLabel` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '价格' COMMENT '价格别名',
  `pricedata` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `spreadgold` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '推广金币',
  `ex_table_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '扩展表',
  `showtpl` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `listtpl` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `comment_open` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `update_money` decimal(6,2) unsigned NOT NULL DEFAULT '0.00',
  `money_open` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `on_day` int(10) unsigned NOT NULL DEFAULT '7' COMMENT '在线天数',
  `area_open` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `list_uniapp` varchar(255) NOT NULL DEFAULT '',
  `show_uniapp` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_fenlei_category_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `catid` (`catid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='版主';
CREATE TABLE `sky_mod_fenlei_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `objectid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级评论',
  `createtime` datetime NOT NULL DEFAULT '2018-07-02 18:52:01' COMMENT '创建时间',
  `content` varchar(225) CHARACTER SET utf8 DEFAULT '' COMMENT '内容',
  `ip` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'ip',
  `ip_city` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '所在城市',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数',
  `imgsdata` text CHARACTER SET utf8 COMMENT '图集',
  PRIMARY KEY (`id`),
  KEY `objectid` (`objectid`,`status`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_fenlei_company` (
  `comid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '标题',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `nickname` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系人',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系电话',
  `weixin` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '微信',
  `wxewm` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '微信二维码',
  `address` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态 1上线 ',
  `createtime` datetime NOT NULL DEFAULT '2019-01-03 19:51:01',
  `typeid` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '类型 0个人 1公司',
  `content` text CHARACTER SET utf8 COMMENT '详情',
  PRIMARY KEY (`comid`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_fenlei_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dingmoney` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '置顶首页费用',
  `hb_on` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `hb_wx_follow` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `update_money` decimal(6,2) unsigned NOT NULL DEFAULT '0.00',
  `rank_on` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '等级折扣',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_fenlei_guest` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `createtime` datetime NOT NULL DEFAULT '2019-02-23 05:31:01' COMMENT '创建时间',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `touserid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户2',
  `objectid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类信息',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `author` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `objectid_userid` (`objectid`,`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4 COMMENT='咨询索引';
CREATE TABLE `sky_mod_fenlei_guestindex` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `createtime` datetime NOT NULL DEFAULT '2019-02-23 05:31:01' COMMENT '创建时间',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `touserid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户2',
  `objectid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类信息',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `objectid_userid` (`objectid`,`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COMMENT='咨询索引';
CREATE TABLE `sky_mod_fenlei_hbconfig` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hb_num` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hb_money` smallint(5) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_fenlei_hongbao` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `money` decimal(9,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fid` (`fid`,`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_fenlei_model` (
  `id` int(10) unsigned NOT NULL,
  `content` mediumtext CHARACTER SET utf8,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_fenlei_tags` (
  `tagid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `total_num` int(10) unsigned NOT NULL DEFAULT '0',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0',
  `gkey` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `gnum` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`tagid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_fenlei_tags_index` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tagid` int(10) unsigned NOT NULL DEFAULT '0',
  `objectid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderindex` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tagid` (`tagid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_fenlei_view` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `objectid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2019-01-09 12:20:21',
  PRIMARY KEY (`id`),
  KEY `objectid` (`objectid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=545 DEFAULT CHARSET=utf8mb4;

eof;
?>