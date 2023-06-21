<?php
$content=<<<eof
CREATE TABLE `sky_mod_freeshop_bankcard` (
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='银行账号';
CREATE TABLE `sky_mod_freeshop_category` (
  `catid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderindex` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) CHARACTER SET utf8 DEFAULT '',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COMMENT='分类';
CREATE TABLE `sky_mod_freeshop_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_money` smallint(5) unsigned NOT NULL DEFAULT '1',
  `post_num` smallint(5) unsigned NOT NULL DEFAULT '10',
  `shop_join_money` smallint(5) unsigned NOT NULL DEFAULT '10',
  `recommend_money` smallint(5) unsigned NOT NULL DEFAULT '1',
  `min_discount` decimal(2,1) unsigned NOT NULL DEFAULT '3.0',
  `max_discount` decimal(2,1) NOT NULL DEFAULT '9.0',
  `min_num` int(10) unsigned NOT NULL DEFAULT '3',
  `max_num` int(10) unsigned DEFAULT '10000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='设置';
CREATE TABLE `sky_mod_freeshop_feeds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `productid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '产品id',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`,`productid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COMMENT='产品-订阅';
CREATE TABLE `sky_mod_freeshop_follow` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商家id',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`,`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COMMENT='产品-关注';
CREATE TABLE `sky_mod_freeshop_invite_account` (
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `money` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `income` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='邀请收入';
CREATE TABLE `sky_mod_freeshop_invite_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `productid` int(10) unsigned NOT NULL DEFAULT '0',
  `money` decimal(9,2) NOT NULL DEFAULT '0.00',
  `to_userid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `productid` (`productid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='邀请奖励日志';
CREATE TABLE `sky_mod_freeshop_order` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `productid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2018-02-07 08:30:01',
  `money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ispay` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `address` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `nickname` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系人',
  `telephone` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系电话',
  `isreceived` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否收货',
  `express` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '快递',
  `israty` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ordercode` varchar(32) NOT NULL DEFAULT '' COMMENT '验证码',
  `recharge_id` int(10) unsigned NOT NULL,
  `paytype` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '支付方式',
  `invite_fsuserid` int(10) unsigned NOT NULL DEFAULT '0',
  `sendtype` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `etime` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`orderid`),
  KEY `productid` (`productid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE,
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COMMENT='订单';
CREATE TABLE `sky_mod_freeshop_order_code` (
  `orderid` int(10) unsigned NOT NULL DEFAULT '0',
  `ordercode` varchar(32) NOT NULL DEFAULT '',
  `isuse` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `checktime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`orderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='订单验证码';
CREATE TABLE `sky_mod_freeshop_order_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `createtime` datetime NOT NULL DEFAULT '2019-02-28 06:01:01',
  `orderid` bigint(20) NOT NULL DEFAULT '0',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `adminid` int(11) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_freeshop_order_raty` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `productid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `createtime` datetime NOT NULL DEFAULT '2019-02-28 10:01:01' COMMENT '创建时间',
  `raty_grade` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '快递',
  `raty_express` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '快递',
  `raty_service` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '服务',
  `raty_quality` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '质量',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '内容',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COMMENT='订单评价';
CREATE TABLE `sky_mod_freeshop_product` (
  `productid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `maxnum` int(10) unsigned NOT NULL DEFAULT '10' COMMENT '库存数',
  `buynum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '购买数',
  `price` decimal(9,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '活动价',
  `market_price` decimal(9,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '原价',
  `stime` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `etime` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `ontime` int(11) NOT NULL DEFAULT '0' COMMENT '下线时间',
  `sendtype` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_num` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2018-04-10 09:12:01',
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `freetime` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `fav_num` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `invite_money` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `discount` decimal(3,1) unsigned NOT NULL DEFAULT '8.0',
  `imgsdata` varchar(360) CHARACTER SET utf8 DEFAULT '' COMMENT '图集',
  `mp4url` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`productid`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COMMENT='闲时促销-产品';
CREATE TABLE `sky_mod_freeshop_product_comment` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='产品评论';
CREATE TABLE `sky_mod_freeshop_product_view` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `productid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2018-02-07 08:30:01',
  PRIMARY KEY (`id`),
  KEY `productid` (`productid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE,
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8mb4 COMMENT='访问';
CREATE TABLE `sky_mod_freeshop_shop` (
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
  `imgurl` varchar(225) NOT NULL DEFAULT '',
  PRIMARY KEY (`shopid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='商家';
CREATE TABLE `sky_mod_freeshop_shop_apply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `nickname` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系人',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '电话',
  `address` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `yyzz` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '营业执照',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '业务介绍',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `per_money` tinyint(3) unsigned NOT NULL DEFAULT '10',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COMMENT='供应商申请';
CREATE TABLE `sky_mod_freeshop_shop_commission` (
  `shopid` int(10) unsigned NOT NULL,
  `per` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `stime` int(10) unsigned NOT NULL DEFAULT '0',
  `etime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`shopid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='佣金';
CREATE TABLE `sky_mod_freeshop_shop_money` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `income` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '总收入',
  `balance` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '可用余额',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='商家资金';
CREATE TABLE `sky_mod_freeshop_shop_money_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL COMMENT '商家',
  `createtime` datetime NOT NULL DEFAULT '2018-08-24 00:00:00' COMMENT '创建时间',
  `money` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4;

eof;
?>