<?php
$content=<<<eof
CREATE TABLE `sky_mod_tutor_bankcard` (
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_tutor_category` (
  `catid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderindex` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) CHARACTER SET utf8 DEFAULT '',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COMMENT='分类';
CREATE TABLE `sky_mod_tutor_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `per_money` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '平台提成',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='配置';
CREATE TABLE `sky_mod_tutor_feeds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `lessonid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '产品id',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`,`lessonid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='课程-订阅';
CREATE TABLE `sky_mod_tutor_follow` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商家id',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`,`shopid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='产品-关注';
CREATE TABLE `sky_mod_tutor_lesson` (
  `lessonid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `catid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `ispass` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '审核状态 1通过 2不通过',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '简介',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '图片',
  `order_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单数',
  `money` decimal(7,2) unsigned NOT NULL DEFAULT '0.00',
  `lesson_num` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '课堂数',
  `per_lesson_money` decimal(7,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '每节课费用',
  `total_num` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `sold_num` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` varchar(2000) NOT NULL DEFAULT '',
  PRIMARY KEY (`lessonid`),
  KEY `catid` (`catid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='课堂';
CREATE TABLE `sky_mod_tutor_order` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `lessonid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `money` decimal(9,2) NOT NULL DEFAULT '0.00',
  `ispay` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `telephone` varchar(15) NOT NULL DEFAULT '',
  `nickname` varchar(15) NOT NULL DEFAULT '',
  `address` varchar(64) NOT NULL DEFAULT '',
  `recharge_id` varchar(64) NOT NULL DEFAULT '',
  `paytype` varchar(12) NOT NULL DEFAULT '',
  `israty` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`orderid`),
  KEY `userid` (`userid`),
  KEY `shopid` (`shopid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='教师订单';
CREATE TABLE `sky_mod_tutor_order_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2021-06-29 20:46:01',
  `content` varchar(225) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='订单日志';
CREATE TABLE `sky_mod_tutor_order_raty` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `lessonid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `raty_content` varchar(225) NOT NULL DEFAULT '',
  `raty_grade` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `shopid` (`shopid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='教师订单评价';
CREATE TABLE `sky_mod_tutor_shop` (
  `shopid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '店名',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '简介',
  `imgurl` varchar(225) NOT NULL DEFAULT 'static/images/no_image.jpg' COMMENT '图片',
  `address` varchar(64) NOT NULL DEFAULT '' COMMENT '地址',
  `createtime` datetime NOT NULL DEFAULT '2017-06-18 18:09:01',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '手机',
  `nickname` varchar(15) NOT NULL DEFAULT '' COMMENT '店主',
  `lat` decimal(9,6) NOT NULL,
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `raty_grade` decimal(3,1) unsigned NOT NULL DEFAULT '8.0',
  `order_num` int(10) unsigned NOT NULL DEFAULT '0',
  `follow_num` int(10) unsigned NOT NULL DEFAULT '0',
  `fee` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '收费服务 ',
  `new_order` int(10) unsigned NOT NULL DEFAULT '0',
  `grade` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`shopid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_tutor_shop_apply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '店名',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '简介',
  `imgurl` varchar(225) NOT NULL DEFAULT 'static/images/no_image.jpg' COMMENT '图片',
  `address` varchar(64) NOT NULL DEFAULT '' COMMENT '地址',
  `createtime` datetime NOT NULL DEFAULT '2017-06-18 18:09:01',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '手机',
  `nickname` varchar(15) NOT NULL DEFAULT '' COMMENT '店主',
  `lat` decimal(9,6) NOT NULL,
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `userno` varchar(20) NOT NULL DEFAULT '',
  `usercard` varchar(225) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_tutor_shop_cert` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `typeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '证件类型',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '证件名称',
  `imgsdata` varchar(666) NOT NULL DEFAULT '' COMMENT '图集',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='商家证件';
CREATE TABLE `sky_mod_tutor_shop_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext,
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='教师详情';
CREATE TABLE `sky_mod_tutor_shop_lesson` (
  `shopid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lessonid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `order_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单数',
  `orderindex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`shopid`),
  KEY `lessonid` (`lessonid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='教师课堂';
CREATE TABLE `sky_mod_tutor_shop_money` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `income` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '总收入',
  `balance` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '可用余额',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_tutor_shop_money_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL COMMENT '商家',
  `createtime` datetime NOT NULL DEFAULT '2018-08-24 00:00:00' COMMENT '创建时间',
  `money` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_tutor_shop_paypwd` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `paypwd` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_tutor_shop_safephone` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

eof;
?>