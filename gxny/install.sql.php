<?php
$content=<<<eof
CREATE TABLE `sky_mod_gxny_admin` (
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
CREATE TABLE `sky_mod_gxny_animal_order` (
  `orderid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户2',
  `animalid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '动物id',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `money` decimal(9,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '订单金额',
  `back_money` decimal(9,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '可返金额',
  `createtime` datetime NOT NULL DEFAULT '2019-02-23 05:31:01' COMMENT '创建时间',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `animal_no` varchar(32) NOT NULL DEFAULT '' COMMENT '动物编号',
  PRIMARY KEY (`orderid`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_gxny_animal_order_task` (
  `taskid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `animalid` int(10) unsigned NOT NULL DEFAULT '0',
  `tablename` varchar(16) NOT NULL DEFAULT 'order',
  `orderid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2022-03-07 01:02:03',
  `updatetime` datetime NOT NULL DEFAULT '2022-03-07 01:02:03',
  `typeid` int(10) unsigned NOT NULL DEFAULT '1',
  `task_action` varchar(16) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) NOT NULL DEFAULT '',
  PRIMARY KEY (`taskid`),
  KEY `shopid` (`shopid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='任务';
CREATE TABLE `sky_mod_gxny_bankcard` (
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
CREATE TABLE `sky_mod_gxny_blog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `productid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderid` int(10) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_num` int(10) unsigned NOT NULL DEFAULT '0',
  `mp4url` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `createtime` datetime NOT NULL DEFAULT '2018-04-10 09:12:01',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `city` varchar(16) NOT NULL DEFAULT '',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `fav_num` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `gold` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '奖励',
  `imgsdata` text CHARACTER SET utf8 COMMENT '图集',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=239 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_gxny_blog_comment` (
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
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_gxny_blog_feeds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `blogid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '新鲜事id',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`,`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=839 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_gxny_blog_view` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `objectid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2019-01-09 12:20:21',
  PRIMARY KEY (`id`),
  KEY `objectid` (`objectid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=800 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_gxny_feeds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `blogid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '新鲜事id',
  `fuserid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`,`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=840 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_gxny_guest` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `createtime` datetime NOT NULL DEFAULT '2019-02-23 05:31:01' COMMENT '创建时间',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户2',
  `productid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类信息',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `author` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `objectid_userid` (`productid`,`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_gxny_guestindex` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `createtime` datetime NOT NULL DEFAULT '2019-02-23 05:31:01' COMMENT '创建时间',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户2',
  `productid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类信息',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `author` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `objectid_userid` (`productid`,`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_gxny_order` (
  `orderid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户2',
  `productid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类信息',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `money` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `createtime` datetime NOT NULL DEFAULT '2019-02-23 05:31:01' COMMENT '创建时间',
  PRIMARY KEY (`orderid`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_gxny_order_task` (
  `taskid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `productid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2022-03-07 01:02:03',
  `updatetime` datetime NOT NULL DEFAULT '2022-03-07 01:02:03',
  `typeid` int(10) unsigned NOT NULL DEFAULT '1',
  `task_action` varchar(16) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) NOT NULL DEFAULT '',
  PRIMARY KEY (`taskid`),
  KEY `shopid` (`shopid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='任务';
CREATE TABLE `sky_mod_gxny_shop` (
  `shopid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopname` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '描述',
  `createtime` datetime NOT NULL DEFAULT '2018-12-07 11:32:01',
  `imgurl` varchar(225) NOT NULL DEFAULT '/static/images/no_image.jpg' COMMENT '图片',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0',
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000' COMMENT '纬度',
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000' COMMENT '经度',
  `nickname` varchar(12) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系电话',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `isrecommend` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `address` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `money` decimal(13,2) unsigned NOT NULL DEFAULT '0.00',
  `balance` decimal(13,2) unsigned NOT NULL DEFAULT '0.00',
  `earnest` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '保证金 ',
  `gonggao` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `content` mediumtext CHARACTER SET utf8,
  PRIMARY KEY (`shopid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_gxny_shop_animal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(16) NOT NULL DEFAULT '0',
  `price` decimal(9,2) NOT NULL DEFAULT '0.00',
  `back_money` decimal(9,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '可退金额',
  `createtime` datetime NOT NULL DEFAULT '2022-05-10 21:30:31',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `total_num` int(10) unsigned NOT NULL DEFAULT '0',
  `sold_num` int(10) unsigned NOT NULL DEFAULT '0',
  `free_num` int(10) unsigned NOT NULL DEFAULT '0',
  `isopen` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '开售',
  `imgurl` varchar(128) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='共享动物';
CREATE TABLE `sky_mod_gxny_shop_category` (
  `catid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL DEFAULT '',
  `description` varchar(128) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `money` decimal(9,2) NOT NULL DEFAULT '0.00',
  `orderindex` int(10) unsigned NOT NULL DEFAULT '0',
  `no` int(10) unsigned NOT NULL DEFAULT '0',
  `caidan` varchar(1024) NOT NULL DEFAULT '',
  PRIMARY KEY (`catid`),
  KEY `shopid` (`shopid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='分类';
CREATE TABLE `sky_mod_gxny_shop_money` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `income` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '总收入',
  `balance` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '可用余额',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_gxny_shop_money_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL COMMENT '商家',
  `createtime` datetime NOT NULL DEFAULT '2018-08-24 00:00:00' COMMENT '创建时间',
  `money` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_gxny_shop_product` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户2',
  `no` varchar(16) NOT NULL DEFAULT '',
  `orderindex` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2019-02-23 05:31:01' COMMENT '创建时间',
  `catid` int(4) unsigned NOT NULL DEFAULT '0',
  `decription` varchar(64) NOT NULL DEFAULT '',
  `isused` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `starttime` datetime NOT NULL DEFAULT '2022-03-04 20:04:01',
  `orderid` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` datetime NOT NULL DEFAULT '2022-03-04 20:01:02',
  `islook` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `iszhong` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '种菜',
  `caidan` varchar(255) NOT NULL DEFAULT '' COMMENT '菜单',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4213 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_gxny_topic` (
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
  `comment_num` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `title` (`title`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='主题';
CREATE TABLE `sky_mod_gxny_topic_index` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `topicid` int(10) unsigned NOT NULL DEFAULT '0',
  `blogid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2019-05-08 05:48:01',
  PRIMARY KEY (`id`),
  KEY `topicid` (`topicid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='主题-帖子';
CREATE TABLE `sky_mod_gxny_useradmin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2022-05-05 13:54:01',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `shopid` (`shopid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户管理者';
CREATE TABLE `sky_mod_gxny_view_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopid` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',
  `lasttime` datetime NOT NULL DEFAULT '2018-07-09 10:49:01',
  `createtime` datetime NOT NULL DEFAULT '2018-02-03 15:09:01',
  `money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`,`lasttime`) USING BTREE,
  KEY `userid_shopid` (`userid`,`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

eof;
?>