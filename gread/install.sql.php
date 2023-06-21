<?php
$content=<<<eof
CREATE TABLE `sky_mod_gread` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_gread_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(225) NOT NULL DEFAULT '' COMMENT '标题',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '简介',
  `createtime` datetime NOT NULL DEFAULT '2017-06-23 09:44:01' COMMENT '发布时间',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '图片',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0',
  `isindex` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext COMMENT '内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_gread_backcart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bookid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `num` smallint(5) unsigned NOT NULL DEFAULT '1',
  `productid` int(10) unsigned NOT NULL DEFAULT '0',
  `order_bookid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid_shopid_bookid` (`userid`,`shopid`,`bookid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_gread_backorder` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2017-06-18 18:19:01',
  `issend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否配送',
  `sendmoney` tinyint(1) NOT NULL DEFAULT '0' COMMENT '配送费用',
  `address` varchar(32) NOT NULL DEFAULT '' COMMENT '地址',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '手机',
  `nickname` varchar(15) NOT NULL DEFAULT '' COMMENT '昵称',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '备注',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `book_money` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`orderid`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_gread_backorder_book` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2017-06-18 18:19:01',
  `bookid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '图书',
  `price` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '订单状态',
  `backtime` datetime NOT NULL DEFAULT '2017-06-18 18:38:01',
  `isback` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否已还',
  `endtime` datetime NOT NULL DEFAULT '2017-06-18 18:38:01',
  `order_bookid` int(10) unsigned NOT NULL DEFAULT '0',
  `productid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE,
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_gread_bankcard` (
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
CREATE TABLE `sky_mod_gread_book` (
  `bookid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '书名',
  `sold_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '销量',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '简介',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '图片',
  `price` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `createtime` datetime NOT NULL DEFAULT '2017-06-18 18:09:01',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `author` varchar(32) NOT NULL DEFAULT '',
  `publisher` varchar(64) NOT NULL DEFAULT '' COMMENT '出版社',
  `content` mediumtext,
  PRIMARY KEY (`bookid`),
  KEY `catid` (`catid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_gread_book_apply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '书名',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '图片',
  `barcode` varchar(64) NOT NULL DEFAULT '' COMMENT '条形码',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `createtime` datetime NOT NULL DEFAULT '2018-10-14 09:37:01' COMMENT '创建时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_gread_book_category` (
  `catid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(16) NOT NULL DEFAULT '',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderindex` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_gread_book_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bookid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2018-07-26 07:16:01',
  `raty` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` text,
  PRIMARY KEY (`id`),
  KEY `bookid` (`bookid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_gread_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bookid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `num` smallint(5) unsigned NOT NULL DEFAULT '1',
  `productid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid_shopid_bookid` (`userid`,`shopid`,`bookid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_gread_guest` (
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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_gread_guestindex` (
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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_gread_order` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2017-06-18 18:19:01',
  `issend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否配送',
  `sendmoney` tinyint(1) NOT NULL DEFAULT '0' COMMENT '配送费用',
  `address` varchar(32) NOT NULL DEFAULT '' COMMENT '地址',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '手机',
  `nickname` varchar(15) NOT NULL DEFAULT '' COMMENT '昵称',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '备注',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `paymoney` decimal(7,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`orderid`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_gread_order_book` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2017-06-18 18:19:01',
  `bookid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '图书',
  `price` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '订单状态',
  `backtime` datetime NOT NULL DEFAULT '2017-06-18 18:38:01',
  `isback` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否已还 0未还 1还中 2已还',
  `endtime` datetime NOT NULL DEFAULT '2017-06-18 18:38:01',
  `productid` int(10) unsigned NOT NULL DEFAULT '0',
  `starttime` datetime DEFAULT '2017-06-18 18:38:01',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE,
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_gread_paylog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2017-06-18 18:19:01',
  `money` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `content` varchar(225) NOT NULL DEFAULT '' COMMENT '内容',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_gread_recharge` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2017-06-18 18:19:01',
  `typeid` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '1会员 2保证金',
  `money` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_gread_recycle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `createtime` datetime NOT NULL DEFAULT '2018-10-14 09:37:01' COMMENT '创建时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `description` varchar(225) NOT NULL DEFAULT '描述',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '书店',
  `nickname` varchar(12) NOT NULL DEFAULT '' COMMENT '昵称',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '手机',
  `address` varchar(64) NOT NULL DEFAULT '' COMMENT '地址',
  `finish_time` datetime NOT NULL DEFAULT '2018-10-14 09:37:01' COMMENT '完成订单',
  `num` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '书籍数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_gread_recycle_shop` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '店名',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '简介',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '图片',
  `address` varchar(64) NOT NULL DEFAULT '' COMMENT '地址',
  `createtime` datetime NOT NULL DEFAULT '2017-06-18 18:09:01',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '手机',
  `nickname` varchar(15) NOT NULL DEFAULT '' COMMENT '店主',
  `lat` decimal(9,6) NOT NULL,
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_gread_shop` (
  `shopid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '店名',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '简介',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '图片',
  `address` varchar(64) NOT NULL DEFAULT '' COMMENT '地址',
  `createtime` datetime NOT NULL DEFAULT '2017-06-18 18:09:01',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '手机',
  `nickname` varchar(15) NOT NULL DEFAULT '' COMMENT '店主',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `sendmoney` decimal(7,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '配送费',
  `lat` decimal(9,6) NOT NULL,
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `usermoney` decimal(7,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '会员费',
  `earnest` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '保证金 ',
  PRIMARY KEY (`shopid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_gread_shop_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店铺',
  `username` varchar(12) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '手机',
  `salt` varchar(6) NOT NULL DEFAULT '' COMMENT '密码串',
  `lastlogin` datetime NOT NULL DEFAULT '2017-12-02 11:08:01',
  `logintime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `username` (`username`) USING BTREE,
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_gread_shop_apply` (
  `shopid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '店名',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '简介',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '图片',
  `address` varchar(64) NOT NULL DEFAULT '' COMMENT '地址',
  `createtime` datetime NOT NULL DEFAULT '2017-06-18 18:09:01',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '手机',
  `nickname` varchar(15) NOT NULL DEFAULT '' COMMENT '店主',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `sendmoney` decimal(7,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '配送费',
  `lat` decimal(9,6) NOT NULL,
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000',
  PRIMARY KEY (`shopid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_gread_shop_earnest` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL COMMENT '商家',
  `createtime` datetime NOT NULL DEFAULT '2018-08-24 00:00:00' COMMENT '创建时间',
  `lasttime` datetime NOT NULL DEFAULT '2018-08-24 00:00:00' COMMENT '更新时间',
  `endtime` datetime NOT NULL DEFAULT '2018-08-24 00:00:00' COMMENT '截止时间',
  `money` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '金额',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_gread_shop_earnest_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL COMMENT '商家',
  `createtime` datetime NOT NULL DEFAULT '2018-08-24 00:00:00' COMMENT '创建时间',
  `money` int(10) NOT NULL DEFAULT '0' COMMENT '金额',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_gread_shop_earnest_refund` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL COMMENT '商家',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2018-08-24 00:00:00' COMMENT '创建时间',
  `finishtime` datetime NOT NULL DEFAULT '2018-08-24 00:00:00' COMMENT '处理时间',
  `money` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '金额',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '内容',
  `bankid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '银行卡',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_gread_shop_incart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bookid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `num` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `shopid_bookid` (`shopid`,`bookid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_gread_shop_inorder` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2017-06-20 15:16:01',
  `money` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `telephone` varchar(15) NOT NULL DEFAULT '',
  `address` varchar(32) NOT NULL DEFAULT '',
  `nickname` varchar(15) NOT NULL DEFAULT '',
  `bookmoney` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `isrecived` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`orderid`),
  KEY `shopid_bookid` (`shopid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_gread_shop_inorder_book` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` int(10) unsigned NOT NULL DEFAULT '0',
  `bookid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `num` smallint(5) unsigned NOT NULL DEFAULT '0',
  `createtime` date NOT NULL DEFAULT '2017-06-20',
  `price` decimal(10,2) unsigned DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `shopid_bookid` (`shopid`,`bookid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_gread_shop_money` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `income` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '总收入',
  `balance` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '可用余额',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_gread_shop_money_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL COMMENT '商家',
  `createtime` datetime NOT NULL DEFAULT '2018-08-24 00:00:00' COMMENT '创建时间',
  `money` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_gread_shop_outcart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bookid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `num` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `shopid_bookid` (`shopid`,`bookid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_gread_shop_outorder` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2017-06-20 15:16:01',
  `money` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `bookmoney` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `telephone` varchar(15) NOT NULL DEFAULT '',
  `address` varchar(32) NOT NULL DEFAULT '',
  `nickname` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`orderid`),
  KEY `shopid_bookid` (`shopid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_gread_shop_outorder_book` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` int(10) unsigned NOT NULL DEFAULT '0',
  `bookid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `num` smallint(5) unsigned NOT NULL DEFAULT '0',
  `createtime` date NOT NULL DEFAULT '2017-06-20',
  PRIMARY KEY (`id`),
  KEY `shopid_bookid` (`shopid`,`bookid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_gread_shop_paypwd` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `paypwd` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_gread_shop_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `bookid` int(10) unsigned NOT NULL DEFAULT '0',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `total_num` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '总量',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `free_num` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '库存',
  `out_num` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '借出',
  `sold_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总销量',
  `catid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `title` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_gread_shop_safephone` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_gread_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `nickname` varchar(15) NOT NULL DEFAULT '' COMMENT '昵称',
  `user_head` varchar(225) NOT NULL DEFAULT '' COMMENT '头像',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '手机',
  `createtime` datetime NOT NULL DEFAULT '2017-06-18 18:19:01',
  `bond` smallint(6) NOT NULL DEFAULT '0' COMMENT '保证金',
  `out_bond` smallint(6) unsigned DEFAULT '0' COMMENT '占用保证金',
  `endtime` datetime NOT NULL DEFAULT '2017-06-18 18:19:01' COMMENT '会员到期时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '订单状态',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `address` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_gread_user_card` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2017-06-21 00:00:00',
  `money` decimal(9,2) NOT NULL DEFAULT '0.00',
  `ispay` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否支付 1已支付 0未支付',
  `isfinish` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `shop_money` decimal(9,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '支付给商家的费用',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_gread_zbook` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '图书封面',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '描述',
  `total_money` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '图书总价',
  `pay_money` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '支付价格',
  `num` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `nickname` varchar(12) NOT NULL DEFAULT '' COMMENT '昵称',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '手机',
  `address` varchar(64) NOT NULL DEFAULT '' COMMENT '地址',
  `ispay` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付状态',
  `createtime` datetime NOT NULL DEFAULT '2018-10-14 09:37:01' COMMENT '创建时间',
  `finish_time` datetime NOT NULL DEFAULT '2018-10-14 09:37:01' COMMENT '完成订单',
  `imgsdata` varchar(360) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

eof;
?>