<?php
$content=<<<eof
CREATE TABLE `sky_mod_exue_admin` (
  `adminid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店铺id',
  `adminname` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '管理员',
  `password` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '密码',
  `salt` char(4) CHARACTER SET utf8 NOT NULL,
  `lasttime` datetime NOT NULL DEFAULT '2018-08-03 16:20:01' COMMENT '上次登录',
  `xlasttime` datetime NOT NULL DEFAULT '2018-08-03 16:20:01' COMMENT '上上次登录',
  PRIMARY KEY (`adminid`),
  KEY `adminname` (`adminname`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_exue_bankcard` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `yhk_name` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '银行名称',
  `yhk_haoma` varchar(24) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '银行卡号码',
  `yhk_huming` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '银行卡户名',
  `telephone` varchar(18) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '电话',
  `yhk_address` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '开户地址',
  `paytype` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '支付方式  支付宝 银行卡 微信 ',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_exue_blog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_num` int(10) unsigned NOT NULL DEFAULT '0',
  `mp4url` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `createtime` datetime NOT NULL DEFAULT '2018-04-10 09:12:01',
  `tcid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `sitecheck` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `fav_num` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `gold` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '奖励',
  `imgsdata` text CHARACTER SET utf8 COMMENT '图集',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_exue_blog_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `objectid` int(10) unsigned NOT NULL DEFAULT '0',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级评论',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2018-07-02 18:52:01',
  `content` text CHARACTER SET utf8,
  `ip` varchar(32) CHARACTER SET utf8 NOT NULL,
  `ip_city` varchar(50) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `objectid` (`objectid`,`status`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_exue_blog_view` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `objectid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2019-01-09 12:20:21',
  PRIMARY KEY (`id`),
  KEY `objectid` (`objectid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_exue_category` (
  `catid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderindex` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) CHARACTER SET utf8 DEFAULT '',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `ex_table_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '扩展表',
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_exue_course` (
  `courseid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '名称',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '图片',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '描述',
  `raty_grade` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '综合评分',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '推荐',
  `ishot` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '热门',
  `isnew` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '最新',
  `market_price` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `price` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `bm_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '报名数',
  `stype` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '类型 1试学  2正式',
  `ke_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '课时',
  `site_status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `site_index` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '首页推荐',
  `createtime` datetime NOT NULL DEFAULT '2020-12-04 01:01:01',
  `content` text COMMENT '内容',
  PRIMARY KEY (`courseid`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_exue_course_feeds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `courseid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '产品id',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`,`courseid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_exue_course_teacher` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tcid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2020-09-02 14:42:44',
  `courseid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tcid` (`tcid`) USING BTREE,
  KEY `courseid` (`courseid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_exue_feeds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `blogid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '新鲜事id',
  `fuserid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`,`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_exue_follow` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商家id',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`,`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_exue_guest` (
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_exue_guestindex` (
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_exue_keplan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商家',
  `tcid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '老师',
  `stid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '学生',
  `ketime` datetime NOT NULL DEFAULT '2020-09-10 09:22:01' COMMENT '上课时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `tcid` (`tcid`) USING BTREE,
  KEY `stid` (`stid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_exue_order` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `courseid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `israty` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `raty_grade` tinyint(3) unsigned NOT NULL DEFAULT '9' COMMENT '综合评分',
  `raty_content` varchar(225) NOT NULL DEFAULT '',
  `raty_time` datetime DEFAULT '2020-09-15 01:01:01',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `ispay` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付状态',
  `money` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `shop_money` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '商家金额',
  `telephone` varchar(18) NOT NULL DEFAULT '',
  `nickname` varchar(15) NOT NULL DEFAULT '',
  `pt_money` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '平台金额',
  `bm_time` datetime NOT NULL DEFAULT '2020-09-01 09:15:01',
  `createtime` datetime NOT NULL DEFAULT '2020-09-01 09:15:01',
  `tcid` int(10) unsigned NOT NULL DEFAULT '0',
  `stid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '学生',
  PRIMARY KEY (`orderid`),
  KEY `courseid` (`courseid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE,
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_exue_report` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `typeid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `reply` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `content` varchar(225) CHARACTER SET utf8 DEFAULT NULL,
  `imgsdata` text CHARACTER SET utf8,
  `createtime` datetime NOT NULL DEFAULT '2019-03-22 03:02:03',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_exue_shop` (
  `shopid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '名称',
  `company` varchar(128) NOT NULL DEFAULT '' COMMENT '公司',
  `address` varchar(64) NOT NULL DEFAULT '' COMMENT '地址',
  `nickname` varchar(16) NOT NULL DEFAULT '' COMMENT '昵称',
  `telephone` varchar(18) NOT NULL DEFAULT '' COMMENT '电话',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '图片',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '描述',
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `raty_grade` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '综合评分',
  `raty_yj` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '硬件评分',
  `raty_jc` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '教师评分',
  `student_num` int(10) unsigned NOT NULL DEFAULT '0',
  `follow_num` int(10) unsigned DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `site_recommend` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `site_index` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '推荐',
  `ishot` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '热门',
  `isnew` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '最新',
  `content` text COMMENT '内容',
  PRIMARY KEY (`shopid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_exue_shop_category` (
  `catid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderindex` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) CHARACTER SET utf8 DEFAULT '',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_exue_shop_commission` (
  `shopid` int(10) unsigned NOT NULL,
  `per` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `stime` int(10) unsigned NOT NULL DEFAULT '0',
  `etime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`shopid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_exue_shop_money` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `income` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '总收入',
  `balance` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '可用余额',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_exue_shop_money_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL COMMENT '商家',
  `createtime` datetime NOT NULL DEFAULT '2018-08-24 00:00:00' COMMENT '创建时间',
  `money` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_exue_shop_safephone` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_exue_shop_student` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `stid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2020-09-09 01:01:01',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `updatetime` datetime DEFAULT '2020-09-09 01:01:01',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_exue_student` (
  `stid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `gender` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '1 男 2女',
  `telephone` varchar(18) NOT NULL DEFAULT '',
  `address` varchar(64) NOT NULL DEFAULT '',
  `truename` varchar(15) NOT NULL DEFAULT '',
  `imgurl` varchar(225) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `description` varchar(225) NOT NULL DEFAULT '',
  `grade` decimal(2,1) unsigned NOT NULL DEFAULT '9.0',
  `createtime` datetime NOT NULL DEFAULT '2020-09-02 14:42:44',
  `age` date DEFAULT '2020-09-07',
  PRIMARY KEY (`stid`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_exue_teacher` (
  `tcid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `nickname` varchar(15) NOT NULL DEFAULT '',
  `telephone` varchar(18) NOT NULL DEFAULT '',
  `address` varchar(64) NOT NULL DEFAULT '',
  `truename` varchar(15) NOT NULL DEFAULT '',
  `imgurl` varchar(225) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `description` varchar(225) NOT NULL DEFAULT '',
  `grade` decimal(2,1) unsigned NOT NULL DEFAULT '9.0',
  `site_recommend` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2020-09-02 14:42:44',
  `content` text,
  PRIMARY KEY (`tcid`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_exue_topic` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2019-05-08 05:48:01',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `isindex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '首页',
  `view_num` int(11) NOT NULL,
  `blog_num` int(11) NOT NULL,
  `description` varchar(225) NOT NULL,
  `imgurl` varchar(225) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_exue_topic_index` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `topicid` int(10) unsigned NOT NULL DEFAULT '0',
  `blogid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2019-05-08 05:48:01',
  PRIMARY KEY (`id`),
  KEY `topicid` (`topicid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_exue_user_teacher` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tcid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `isdefault` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

eof;
?>