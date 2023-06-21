<?php
$content=<<<eof
CREATE TABLE `sky_mod_pdd_admin` (
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
CREATE TABLE `sky_mod_pdd_article` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '标题',
  `shopid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商家',
  `catid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '大类',
  `shop_catid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商家分类',
  `love_num` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '喜欢数',
  `fav_num` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数',
  `forward_num` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '转发数',
  `description` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `createtime` datetime NOT NULL DEFAULT '2019-01-30 08:01:01' COMMENT '创建时间',
  `updatetime` datetime NOT NULL DEFAULT '2019-01-30 08:01:01' COMMENT '更新时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态 1显示',
  `comment_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '评论数量',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '图片',
  `grade` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `isrecommend` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `view_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '查看数',
  `isnew` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '最新 1是 0否',
  `ishot` tinyint(4) NOT NULL DEFAULT '0' COMMENT '热门 1是 0否',
  `author` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '作者',
  `videourl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '视频地址',
  `price` decimal(10,0) unsigned NOT NULL DEFAULT '0' COMMENT '赏金',
  `site_isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '站点推荐',
  `site_isnew` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '站点最新',
  `imgsdata` text CHARACTER SET utf8,
  PRIMARY KEY (`id`),
  KEY `catid` (`catid`) USING BTREE,
  KEY `shopid` (`shopid`,`shop_catid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_article_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `objectid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商家',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级评论',
  `createtime` datetime NOT NULL DEFAULT '2018-07-02 18:52:01' COMMENT '创建时间',
  `content` text CHARACTER SET utf8 COMMENT '内容',
  `ip` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'ip',
  `ip_city` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '所在城市',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数',
  `imgsdata` text CHARACTER SET utf8 COMMENT '图集',
  PRIMARY KEY (`id`),
  KEY `objectid` (`objectid`,`status`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_article_data` (
  `id` int(10) unsigned NOT NULL,
  `content` mediumtext CHARACTER SET utf8,
  `shopid` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_bankcard` (
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
CREATE TABLE `sky_mod_pdd_blog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_num` int(10) unsigned NOT NULL DEFAULT '0',
  `mp4url` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `createtime` datetime NOT NULL DEFAULT '2018-04-10 09:12:01',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `imgsdata` text CHARACTER SET utf8 COMMENT '图集',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_blog_comment` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_blog_view` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `objectid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2019-01-09 12:20:21',
  PRIMARY KEY (`id`),
  KEY `objectid` (`objectid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_cart` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `productid` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  `createtime` datetime NOT NULL DEFAULT '2019-01-30 08:01:01' COMMENT '创建时间',
  `userid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `amount` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单数量',
  `ksid` int(11) NOT NULL DEFAULT '0' COMMENT '款式',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `createtime` (`createtime`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_category` (
  `catid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderindex` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) CHARACTER SET utf8 DEFAULT '',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_cbd` (
  `cbdid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '简介',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '图片',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `orderindex` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cbdid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_cbd_shop` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cbdid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商圈',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店铺',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `orderindex` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `cbdid` (`cbdid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siteid` int(11) unsigned NOT NULL DEFAULT '1',
  `title` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '数量',
  `end_time` datetime NOT NULL DEFAULT '2099-02-01 03:01:02' COMMENT '截止日期',
  `dateline` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `shopid` int(11) NOT NULL DEFAULT '0' COMMENT '店铺Id',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `get_num` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '领取人数',
  `use_num` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '使用人数',
  `lower_money` decimal(9,1) unsigned NOT NULL DEFAULT '0.0' COMMENT '最低使用价格',
  `limit_num` int(11) NOT NULL DEFAULT '0' COMMENT '限制领取数',
  `typeid` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '类型 1无需取券 2需要取券',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_coupon_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siteid` int(11) NOT NULL DEFAULT '1',
  `coupon_id` int(11) NOT NULL DEFAULT '0' COMMENT '优惠券',
  `shopid` int(11) NOT NULL DEFAULT '0' COMMENT '所属店铺',
  `userid` int(11) unsigned NOT NULL DEFAULT '0',
  `dateline` int(11) NOT NULL DEFAULT '0' COMMENT '领取时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '使用状态',
  `notice_time` int(11) NOT NULL DEFAULT '0' COMMENT '上次通知',
  `money` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `lower_money` decimal(9,2) DEFAULT '0.00' COMMENT '最低使用价格',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '截止日期',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_express_fee` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `fmoney` decimal(6,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '首重',
  `amoney` decimal(6,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '加重',
  `shopid` int(11) NOT NULL DEFAULT '0' COMMENT '店铺',
  `fweight` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '首重',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_express_fee_city` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级',
  `areaid` int(11) NOT NULL DEFAULT '0' COMMENT '区域',
  `shopid` int(11) NOT NULL DEFAULT '0' COMMENT '店铺',
  PRIMARY KEY (`id`),
  KEY `areaid` (`areaid`,`shopid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_guest` (
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_guestindex` (
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_order` (
  `orderid` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderno` varchar(36) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '订单号',
  `status` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `israty` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否评论 0否 1是',
  `ispay` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否支付 1是 ',
  `userid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `isreceived` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否接收 0 否 1是',
  `money` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '订单价格',
  `user_address_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收货地址',
  `express_no` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '物流号',
  `goods_money` decimal(15,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '产品价格',
  `express_money` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '快递价格',
  `discount_money` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '折扣价格',
  `coupon_id` int(11) NOT NULL DEFAULT '0' COMMENT '优惠券',
  `coupon_money` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '优惠券价格',
  `comment` varchar(225) CHARACTER SET utf8 NOT NULL COMMENT '评论内容',
  `paytype` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '支付方式',
  `whocancel` varchar(16) CHARACTER SET utf8 NOT NULL COMMENT '谁取消的',
  `daySn` smallint(6) NOT NULL DEFAULT '0' COMMENT '每日订单排序',
  `total_num` smallint(5) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2017-07-02 12:43:01',
  `recharge_id` int(10) unsigned NOT NULL,
  `weight` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `fromsite` varchar(12) CHARACTER SET utf8 NOT NULL DEFAULT 'b2b' COMMENT '来源站点 b2c 独立建站  b2b平台订单',
  `ispin` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pin_num` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '参与人数',
  `productid` int(10) unsigned NOT NULL DEFAULT '0',
  `pin_success` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '拼团成功',
  `pin_orderid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`orderid`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `orderno` (`orderno`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_order_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) unsigned NOT NULL DEFAULT '0',
  `truename` char(8) CHARACTER SET utf8 DEFAULT NULL,
  `telephone` char(12) CHARACTER SET utf8 DEFAULT NULL,
  `address` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  `createtime` datetime NOT NULL DEFAULT '2017-07-02 12:43:01',
  `province_id` int(11) NOT NULL DEFAULT '0',
  `city_id` int(11) NOT NULL DEFAULT '0',
  `town_id` int(11) NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_order_code` (
  `orderid` int(10) unsigned NOT NULL DEFAULT '0',
  `ordercode` varchar(32) NOT NULL DEFAULT '',
  `isuse` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`orderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_order_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2018-07-17 09:01:01',
  `updatetime` datetime NOT NULL DEFAULT '2018-07-17 09:01:01',
  `content` mediumtext CHARACTER SET utf8,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_order_log` (
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
CREATE TABLE `sky_mod_pdd_order_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) unsigned NOT NULL DEFAULT '0',
  `productid` int(11) NOT NULL DEFAULT '0',
  `price` decimal(13,2) NOT NULL DEFAULT '0.00',
  `amount` int(11) NOT NULL DEFAULT '1',
  `createtime` datetime NOT NULL DEFAULT '2017-07-02 12:44:41',
  `ksid` int(11) NOT NULL DEFAULT '0' COMMENT '款式id',
  `iscomment` tinyint(4) NOT NULL DEFAULT '0',
  `raty_grade` tinyint(4) NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`) USING BTREE,
  KEY `productid` (`productid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_order_raty` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `createtime` datetime NOT NULL DEFAULT '2019-02-28 10:01:01' COMMENT '创建时间',
  `raty_express` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '快递',
  `raty_service` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '服务',
  `raty_quality` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '质量',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '内容',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `shop_catid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店铺分类',
  `catid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '图片',
  `price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '价格',
  `iswindow` tinyint(3) unsigned DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态 1上架',
  `weight` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `createtime` datetime NOT NULL DEFAULT '2019-01-30 08:01:01' COMMENT '创建时间',
  `updatetime` datetime NOT NULL DEFAULT '2019-01-30 08:01:01' COMMENT '更新时间',
  `ks_label_name` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '颜色' COMMENT '款式名称',
  `ks_label_size` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '尺寸' COMMENT '款式尺寸',
  `month_buy_num` int(10) unsigned NOT NULL DEFAULT '0',
  `videourl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `isnew` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '最新',
  `ishot` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '最热',
  `isrecommend` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '推荐',
  `total_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '库存',
  `buy_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '销量',
  `lower_price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '促销价',
  `market_price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '市场价',
  `isksid` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否有款式',
  `pt_open` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '开启拼团',
  `pt_price` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '拼团价格',
  `pt_min` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最低人数',
  `view_num` int(10) unsigned NOT NULL,
  `imgsdata` text CHARACTER SET utf8,
  `videobg` varchar(255) NOT NULL DEFAULT '',
  `videoid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `catid_status` (`catid`,`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=632 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_product_data` (
  `id` int(10) unsigned NOT NULL,
  `content` mediumtext CHARACTER SET utf8,
  `shopid` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_product_ks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned DEFAULT '0',
  `productid` int(11) unsigned NOT NULL COMMENT '产品',
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `size` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '尺寸',
  `price` decimal(13,2) unsigned NOT NULL DEFAULT '0.00',
  `createtime` datetime NOT NULL DEFAULT '2019-01-30 08:01:01' COMMENT '创建时间',
  `total_num` int(11) NOT NULL DEFAULT '0',
  `buy_num` int(11) NOT NULL DEFAULT '0',
  `imgurl` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `productid` (`productid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_report` (
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
CREATE TABLE `sky_mod_pdd_shop` (
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
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系电话',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `isrecommend` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `address` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `fav_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数',
  `banner` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'banner',
  `buy_num` int(10) unsigned NOT NULL DEFAULT '0',
  `month_buy_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '月售',
  `window_products` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '橱窗商品',
  `zzdata` text CHARACTER SET utf8 COMMENT '资质文件',
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
  `express_money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '快递费',
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
  `product_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '产品数量',
  PRIMARY KEY (`shopid`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_shop_apply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `nickname` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系人',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '电话',
  `address` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `yyzz` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '营业执照',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '业务介绍',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_shop_category` (
  `catid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderindex` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) CHARACTER SET utf8 DEFAULT '',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_shop_earnest` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL COMMENT '商家',
  `createtime` datetime NOT NULL DEFAULT '2018-08-24 00:00:00' COMMENT '创建时间',
  `lasttime` datetime NOT NULL DEFAULT '2018-08-24 00:00:00' COMMENT '更新时间',
  `endtime` datetime NOT NULL DEFAULT '2018-08-24 00:00:00' COMMENT '截止时间',
  `money` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '金额',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_shop_earnest_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL COMMENT '商家',
  `createtime` datetime NOT NULL DEFAULT '2018-08-24 00:00:00' COMMENT '创建时间',
  `money` int(10) NOT NULL DEFAULT '0' COMMENT '金额',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_shop_earnest_refund` (
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
CREATE TABLE `sky_mod_pdd_shop_money` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `income` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '总收入',
  `balance` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '可用余额',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_shop_money_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL COMMENT '商家',
  `createtime` datetime NOT NULL DEFAULT '2018-08-24 00:00:00' COMMENT '创建时间',
  `money` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_shop_notice` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(11) unsigned NOT NULL DEFAULT '1',
  `dateline` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `type_id` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '消息类型',
  `status` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '状态 0未读',
  `adminid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '管理员',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商家',
  `content` text CHARACTER SET utf8 NOT NULL COMMENT '消息内容',
  `linkurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '链接',
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '通知' COMMENT '小标题',
  PRIMARY KEY (`id`),
  KEY `userid` (`adminid`,`dateline`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_shop_openbind` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `k` varchar(32) NOT NULL DEFAULT '',
  `content` text,
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_pdd_shop_paypwd` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `paypwd` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_pdd_shop_product_category` (
  `catid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderindex` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) CHARACTER SET utf8 DEFAULT '',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_shop_safephone` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_video` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '标题',
  `url` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '视频地址',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '大小',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态 2显示',
  `shop_cid` int(11) NOT NULL DEFAULT '0' COMMENT '商家分类',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '视频截图',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pdd_view_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopid` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',
  `lasttime` datetime NOT NULL DEFAULT '2018-07-09 10:49:01',
  `createtime` datetime NOT NULL DEFAULT '2018-02-03 15:09:01',
  `money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`,`lasttime`) USING BTREE,
  KEY `userid_shopid` (`userid`,`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

eof;
?>