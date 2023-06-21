<?php
$content=<<<eof
CREATE TABLE `sky_mod_olprint_admin` (
  `adminid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店铺id',
  `adminname` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '管理员',
  `password` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '密码',
  `salt` char(4) CHARACTER SET utf8 NOT NULL,
  `lasttime` datetime NOT NULL DEFAULT '2018-08-03 16:20:01' COMMENT '上次登录',
  `xlasttime` datetime NOT NULL DEFAULT '2018-08-03 16:20:01' COMMENT '上上次登录',
  `siteid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`adminid`),
  KEY `adminname` (`adminname`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_olprint_bankcard` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `yhk_name` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '银行名称',
  `yhk_haoma` varchar(24) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '银行卡号码',
  `yhk_huming` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '银行卡户名',
  `telephone` varchar(18) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '电话',
  `yhk_address` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '开户地址',
  `paytype` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '支付方式  支付宝 银行卡 微信 ',
  `siteid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_olprint_book` (
  `bookid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '标题',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '图片',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `money` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '金额',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态 1上线 ',
  `catid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类',
  `isindex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '首页推荐',
  `catding` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '分类置顶',
  `isrecommend` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '推荐',
  `createtime` datetime NOT NULL DEFAULT '2019-01-02 13:01:35',
  `view_num` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '访问人数',
  `comment_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  `page_num` smallint(5) unsigned NOT NULL DEFAULT '1',
  `print_num` int(10) unsigned NOT NULL,
  `fav_num` int(10) unsigned NOT NULL DEFAULT '0',
  `xtime` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1上学期 2下学期',
  `siteid` int(10) unsigned NOT NULL DEFAULT '1',
  `fileurl` varchar(225) NOT NULL DEFAULT '',
  `content` text CHARACTER SET utf8 COMMENT '详情',
  PRIMARY KEY (`bookid`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_olprint_category` (
  `catid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderindex` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) CHARACTER SET utf8 DEFAULT '',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `ex_table_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '扩展表',
  `siteid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_olprint_guest` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `createtime` datetime NOT NULL DEFAULT '2019-02-23 05:31:01' COMMENT '创建时间',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户2',
  `productid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类信息',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `author` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `ukey` varchar(12) NOT NULL DEFAULT '',
  `siteid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `objectid_userid` (`productid`,`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_olprint_guestindex` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `createtime` datetime NOT NULL DEFAULT '2019-02-23 05:31:01' COMMENT '创建时间',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户2',
  `productid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类信息',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `author` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `ukey` varchar(12) NOT NULL DEFAULT '',
  `siteid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `objectid_userid` (`productid`,`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_olprint_openbind` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tablename` varchar(32) NOT NULL DEFAULT '',
  `objectid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `description` varchar(225) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(32) NOT NULL DEFAULT '',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopname` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `objectid` (`objectid`,`tablename`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_olprint_order` (
  `orderid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `ptype` smallint(5) unsigned NOT NULL DEFAULT '1',
  `page_num` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '页数',
  `print_num` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '份数',
  `sendtype` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配送',
  `send_money` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '配货费用',
  `page_money` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '每页费用',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `shop_money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00',
  `createtime` datetime NOT NULL DEFAULT '2010-10-14 01:02:03',
  `ispay` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `siteid` int(10) unsigned NOT NULL DEFAULT '0',
  `nickname` varchar(15) NOT NULL DEFAULT '',
  `address` varchar(15) NOT NULL DEFAULT '',
  `telephone` varchar(15) NOT NULL DEFAULT '',
  `bookid` int(10) unsigned NOT NULL DEFAULT '0',
  `ubook_money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '共享的用户分成',
  `israty` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `recharge_id` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(2255) NOT NULL DEFAULT '',
  `fileurl` varchar(225) NOT NULL DEFAULT '',
  `imgsdata` varchar(1800) NOT NULL DEFAULT '' COMMENT '图集',
  `imgsdatazip` varchar(225) NOT NULL DEFAULT '' COMMENT '文件集',
  PRIMARY KEY (`orderid`),
  KEY `shopid` (`shopid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_olprint_shop` (
  `shopid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopname` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '商家名称',
  `catid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类',
  `title` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '标题',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `createtime` datetime NOT NULL DEFAULT '2018-12-07 11:32:01',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '/static/images/no_image.jpg' COMMENT '图片',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_num` int(10) unsigned NOT NULL DEFAULT '0',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0',
  `sc_id` int(10) unsigned NOT NULL DEFAULT '0',
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000' COMMENT '纬度',
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000' COMMENT '经度',
  `nickname` varchar(12) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系电话',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `isrecommend` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `address` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `fav_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数',
  `banner` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'banner',
  `buy_num` int(10) unsigned NOT NULL DEFAULT '0',
  `month_buy_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '月售',
  `window_products` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '橱窗商品',
  `sendtype` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '发货方式',
  `money` decimal(13,2) unsigned NOT NULL DEFAULT '0.00',
  `balance` decimal(13,2) unsigned NOT NULL DEFAULT '0.00',
  `earnest` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '保证金 ',
  `gonggao` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `raty_grade` decimal(9,1) unsigned NOT NULL DEFAULT '10.0' COMMENT '评分',
  `raty_grade_product` decimal(9,1) unsigned NOT NULL DEFAULT '10.0' COMMENT '产品评分',
  `raty_grade_express` decimal(3,1) unsigned NOT NULL DEFAULT '10.0' COMMENT '物流评分',
  `yystart` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT '00:01' COMMENT '营业开始',
  `yyend` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT '23:59' COMMENT '营业结束',
  `yystatus` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `express_money` decimal(11,2) unsigned NOT NULL DEFAULT '3.00' COMMENT '快递费',
  `cspf` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '超时赔付',
  `chainshop` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '连锁店',
  `avg_price` decimal(9,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '均价',
  `content` mediumtext CHARACTER SET utf8,
  `send_time` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '送货速度',
  `send_startprice` decimal(9,1) unsigned NOT NULL DEFAULT '0.0' COMMENT '起送价',
  `isnew` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '新店',
  `discount_new` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '新人特惠',
  `discount_goods` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '单品折扣',
  `discount_coupon` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '满减活动',
  `zzdata` text CHARACTER SET utf8 COMMENT '资质文件',
  `siteid` int(10) unsigned NOT NULL DEFAULT '0',
  `shoptype` varchar(12) NOT NULL DEFAULT 'b2b',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `isku` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`shopid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_olprint_shop_apply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `nickname` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系人',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '电话',
  `address` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `yyzz` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '营业执照',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '业务介绍',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `siteid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_olprint_shop_commission` (
  `shopid` int(10) unsigned NOT NULL,
  `per` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `stime` int(10) unsigned NOT NULL DEFAULT '0',
  `etime` int(10) unsigned NOT NULL DEFAULT '0',
  `siteid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`shopid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_olprint_shop_money` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `income` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '总收入',
  `balance` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '可用余额',
  `siteid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_olprint_shop_money_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL COMMENT '商家',
  `createtime` datetime NOT NULL DEFAULT '2018-08-24 00:00:00' COMMENT '创建时间',
  `money` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '内容',
  `siteid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_olprint_shop_paypwd` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `paypwd` varchar(32) NOT NULL DEFAULT '',
  `siteid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_olprint_shop_ptype` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ptype` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户2',
  `siteid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(32) NOT NULL DEFAULT '',
  `start_money` decimal(9,2) NOT NULL DEFAULT '0.00',
  `page_money` decimal(9,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_olprint_shop_safephone` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `siteid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

eof;
?>