<?php
$content=<<<eof
CREATE TABLE `sky_mod_gold_order` (
  `orderid` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单',
  `productid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderno` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `status` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `israty` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否评论 0否 1是',
  `userid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `isreceived` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否接收 0 否 1是',
  `gold` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '金币',
  `express_no` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '物流号',
  `comment` varchar(225) CHARACTER SET utf8 NOT NULL COMMENT '备注',
  `whocancel` varchar(16) CHARACTER SET utf8 NOT NULL COMMENT '谁取消的',
  `createtime` datetime NOT NULL DEFAULT '2017-07-02 12:43:01',
  `nickname` varchar(12) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `address` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`orderid`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_gold_order_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `createtime` datetime NOT NULL DEFAULT '2019-02-28 06:01:01',
  `orderid` bigint(20) NOT NULL DEFAULT '0',
  `content` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `adminid` int(11) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_gold_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '图片',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态 1上架',
  `createtime` datetime NOT NULL DEFAULT '2019-01-30 08:01:01' COMMENT '创建时间',
  `isnew` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '最新',
  `ishot` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '最热',
  `isrecommend` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '推荐',
  `total_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '库存',
  `buy_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '销量',
  `gold` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '金币数量',
  `market_price` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '市场价',
  `content` mediumtext CHARACTER SET utf8 COMMENT '内容',
  PRIMARY KEY (`id`),
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

eof;
?>