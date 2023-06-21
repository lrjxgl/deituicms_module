<?php
$content=<<<eof
CREATE TABLE `sky_mod_ershou_bankcard` (
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COMMENT='银行账号';
CREATE TABLE `sky_mod_ershou_baojia` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `productid` int(10) unsigned NOT NULL DEFAULT '0',
  `money` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2022-11-12 06:51:42',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `shopid` (`shopid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='报价';
CREATE TABLE `sky_mod_ershou_category` (
  `catid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderindex` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `ex_table_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '扩展表',
  `list_tpl` varchar(32) NOT NULL DEFAULT '',
  `show_tpl` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_ershou_config` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='设置';
CREATE TABLE `sky_mod_ershou_feeds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `objectid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'objectid',
  `tablename` varchar(32) NOT NULL DEFAULT '',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='产品-订阅';
CREATE TABLE `sky_mod_ershou_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2022-05-05 09:03:01',
  `tablename` varchar(32) NOT NULL DEFAULT '',
  `objectid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COMMENT='访问历史';
CREATE TABLE `sky_mod_ershou_order` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `productid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
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
  `recharge_id` int(10) unsigned NOT NULL DEFAULT '0',
  `paytype` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '支付方式',
  `invite_fsuserid` int(10) unsigned NOT NULL DEFAULT '0',
  `user_address_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`orderid`),
  KEY `productid` (`productid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_ershou_order_raty` (
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COMMENT='订单评价';
CREATE TABLE `sky_mod_ershou_product` (
  `productid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `catid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '类别',
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
  PRIMARY KEY (`productid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='产品';
CREATE TABLE `sky_mod_ershou_product_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `objectid` int(10) unsigned NOT NULL DEFAULT '0',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级评论',
  `createtime` datetime NOT NULL DEFAULT '2018-07-02 18:52:01',
  `content` varchar(225) NOT NULL DEFAULT '',
  `ip` varchar(32) CHARACTER SET utf8 NOT NULL,
  `ip_city` varchar(50) CHARACTER SET utf8 NOT NULL,
  `imgurl` varchar(128) NOT NULL DEFAULT '',
  `imgsdata` varchar(512) NOT NULL DEFAULT '',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `objectid` (`objectid`,`status`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COMMENT='产品评论';
CREATE TABLE `sky_mod_ershou_shop` (
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='商家';
CREATE TABLE `sky_mod_ershou_shop_apply` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='供应商申请';
CREATE TABLE `sky_mod_ershou_shop_commission` (
  `shopid` int(10) unsigned NOT NULL,
  `per` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `stime` int(10) unsigned NOT NULL DEFAULT '0',
  `etime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`shopid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='佣金';
CREATE TABLE `sky_mod_ershou_shop_money` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `income` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '总收入',
  `balance` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '可用余额',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='商家资金';
CREATE TABLE `sky_mod_ershou_shop_money_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL COMMENT '商家',
  `createtime` datetime NOT NULL DEFAULT '2018-08-24 00:00:00' COMMENT '创建时间',
  `money` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4;

eof;
?>