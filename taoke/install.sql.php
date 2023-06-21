<?php
$content=<<<eof
CREATE TABLE `sky_mod_taoke` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `xfrom` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT 'taobao' COMMENT '推广平台',
  `title` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `istk` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否已转换',
  `tk_end` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '淘客结束时间',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '封面',
  `price` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `sold_num` int(10) unsigned NOT NULL DEFAULT '0',
  `tb_numid` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '淘宝产品id',
  `tb_url` varchar(325) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '淘宝产品链接',
  `tb_cat` varchar(32) CHARACTER SET utf8 DEFAULT '' COMMENT '淘宝分类',
  `tk_url` varchar(325) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '淘客产品链接',
  `tk_pwd` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '淘客口令',
  `juan_id` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '优惠券ID',
  `juan_total` int(10) unsigned NOT NULL DEFAULT '0',
  `juan_num` int(10) unsigned NOT NULL DEFAULT '0',
  `juan_url` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '领卷链接',
  `juan_url_base` varchar(225) CHARACTER SET utf8 NOT NULL,
  `juan_pwd` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '领券口令',
  `juan_money` decimal(6,2) NOT NULL DEFAULT '0.00' COMMENT '券价值',
  `juan_end` date NOT NULL DEFAULT '2017-11-07',
  `juan_start` date NOT NULL DEFAULT '2017-11-07',
  `yj_money` decimal(7,2) unsigned NOT NULL DEFAULT '0.00',
  `yj_bl` smallint(5) unsigned NOT NULL DEFAULT '0',
  `seller_id` varchar(12) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `seller_ww` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '卖家旺旺',
  `shop_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '平台类型',
  `shop_name` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `isindex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '首页推荐',
  `ishot` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '热门推荐',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '推荐',
  `fav_num` int(10) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext CHARACTER SET utf8 COMMENT '内容',
  `goods_sign` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `catid` (`catid`) USING BTREE,
  KEY `tb_numid` (`tb_numid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=66774 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_taoke_baodan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `xfrom` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT 'taobao' COMMENT '推广平台',
  `orderno` varchar(32) NOT NULL DEFAULT '' COMMENT '订单号',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `orderno` (`orderno`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_taoke_category` (
  `catid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `orderindex` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `description` varchar(225) NOT NULL DEFAULT '',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `tags` varchar(525) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `tags_need` varchar(12) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '标签前缀',
  `imgurl` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'logo',
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_taoke_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `appkey` varchar(64) CHARACTER SET utf8 NOT NULL,
  `secretKey` varchar(64) CHARACTER SET utf8 NOT NULL,
  `tkuserid` varchar(32) CHARACTER SET utf8 NOT NULL,
  `tktext` varchar(64) CHARACTER SET utf8 NOT NULL,
  `zoneid` varchar(64) NOT NULL,
  `tags` text CHARACTER SET utf8 COMMENT '热门标签',
  `shoptype` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '店铺类型',
  `opmode` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '运营模式 0全自动 1手动 ',
  `flsets` varchar(255) NOT NULL DEFAULT '' COMMENT '返利设置',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='配置';
CREATE TABLE `sky_mod_taoke_group` (
  `gid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `gkey` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '标签',
  `gnum` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '记录数',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`gid`),
  KEY `gkey` (`gkey`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_taoke_group_product` (
  `gpid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gid` int(10) unsigned NOT NULL DEFAULT '0',
  `productid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderindex` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`gpid`),
  KEY `gid` (`gid`) USING BTREE,
  KEY `productid` (`productid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_taoke_love` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `productid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `k` varchar(16) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`,`productid`) USING BTREE,
  KEY `productid` (`productid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_taoke_order` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(225) NOT NULL DEFAULT '' COMMENT '商品标题',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '商品图片',
  `productid` varchar(32) NOT NULL DEFAULT '' COMMENT '商品ID',
  `createtime` datetime NOT NULL DEFAULT '2019-12-14 11:30:01',
  `orderno` varchar(225) NOT NULL DEFAULT '' COMMENT '订单号',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '订单金额',
  `income` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '预估金额',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `orderstatus` varchar(12) NOT NULL DEFAULT '',
  `isback` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否返利了',
  `backmoney` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '返利金额',
  `k` varchar(16) NOT NULL DEFAULT '',
  `issd` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '晒单',
  `isbd` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '报单',
  PRIMARY KEY (`orderid`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_taoke_pdd_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `appkey` varchar(64) CHARACTER SET utf8 NOT NULL,
  `secretKey` varchar(64) CHARACTER SET utf8 NOT NULL,
  `pid` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_taoke_searchcache` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `objectid` bigint(20) NOT NULL DEFAULT '0',
  `goods_sign` varchar(64) NOT NULL DEFAULT '',
  `etime` date NOT NULL DEFAULT '2019-12-20',
  `k` varchar(16) NOT NULL DEFAULT 'taobao',
  `content` mediumtext,
  `title` varchar(225) NOT NULL DEFAULT '',
  `price` decimal(11,2) NOT NULL DEFAULT '0.00',
  `yj_bl` int(10) unsigned NOT NULL DEFAULT '0',
  `yj_money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0',
  `sold_num` int(10) unsigned NOT NULL DEFAULT '0',
  `juan_money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00',
  `imgurl` varchar(225) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `isrecommend` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ishot` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `objectid` (`objectid`,`k`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_taoke_shaidan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2019-12-26 12:18:19',
  `orderno` varchar(32) NOT NULL DEFAULT '' COMMENT '订单号',
  `content` varchar(225) NOT NULL DEFAULT '' COMMENT '内容',
  `imgsdata` text NOT NULL COMMENT '图集',
  `iswin` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否中奖',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `orderno` (`orderno`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_taoke_shop` (
  `shopid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(12) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `linkurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `buy_num` int(10) unsigned NOT NULL DEFAULT '0',
  `grade` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`shopid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_taoke_shop_type` (
  `catid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `orderindex` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `siteid` int(10) unsigned NOT NULL DEFAULT '0',
  `tags` varchar(525) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `tags_need` varchar(12) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '标签前缀',
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_taoke_user_money` (
  `userid` int(10) unsigned NOT NULL,
  `income` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '总收入',
  `balance` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '可用余额',
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_taoke_user_money_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL COMMENT '商家',
  `createtime` datetime NOT NULL DEFAULT '2018-08-24 00:00:00' COMMENT '创建时间',
  `money` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '内容',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

eof;
?>