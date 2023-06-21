<?php
$content=<<<eof
CREATE TABLE `sky_mod_fxa_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL,
  `content` varchar(225) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COMMENT='提现';
CREATE TABLE `sky_mod_fxa_order` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `productid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '产品',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商家',
  `ispay` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付状态',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `fx_money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '分销收益',
  `shop_money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '商家收益',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` datetime NOT NULL DEFAULT '2019-12-16 10:00:01' COMMENT '创建时间',
  `updatetime` datetime NOT NULL DEFAULT '2019-12-16 10:00:01',
  `invite_userid` int(10) unsigned NOT NULL DEFAULT '0',
  `usetime` int(10) unsigned NOT NULL DEFAULT '0',
  `isback` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '已返利',
  `send_type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `telephone` varchar(15) NOT NULL DEFAULT '',
  `nickname` varchar(12) NOT NULL DEFAULT '',
  `address` varchar(64) NOT NULL DEFAULT '',
  `express_no` varchar(64) NOT NULL DEFAULT '' COMMENT '快递单号',
  PRIMARY KEY (`orderid`),
  KEY `productid` (`productid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COMMENT='商家';
CREATE TABLE `sky_mod_fxa_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL DEFAULT '' COMMENT '标题',
  `description` varchar(255) NOT NULL DEFAULT '',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商家',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `price` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `fx_money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '分销收益',
  `sold_num` int(10) unsigned NOT NULL DEFAULT '0',
  `shop_money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '商家收益',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '封面图片',
  `etime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '截止时间',
  `usedesc` varchar(225) NOT NULL DEFAULT '' COMMENT '使用说明',
  `mp4url` varchar(225) NOT NULL DEFAULT '' COMMENT '视频',
  `send_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '1到店 0配送',
  `tpl` varchar(64) NOT NULL DEFAULT '',
  `imgsdata` varchar(512) DEFAULT '' COMMENT '内容图集',
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='产品';
CREATE TABLE `sky_mod_fxa_shop` (
  `shopid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL DEFAULT '' COMMENT '标题',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT 'logo',
  `telephone` varchar(18) NOT NULL DEFAULT '' COMMENT '电话',
  `address` varchar(128) NOT NULL DEFAULT '' COMMENT '地址',
  `shopno` varchar(225) NOT NULL DEFAULT '' COMMENT '营业执照号码',
  `yyzh` varchar(225) NOT NULL DEFAULT '' COMMENT '营业执照',
  `nickname` varchar(18) NOT NULL DEFAULT '' COMMENT '联系人',
  PRIMARY KEY (`shopid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='商家';
CREATE TABLE `sky_mod_fxa_shop_admin` (
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
CREATE TABLE `sky_mod_fxa_tixian` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL,
  `money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '账号余额',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `re_type` varchar(255) NOT NULL DEFAULT '' COMMENT '账户类型',
  `re_username` varchar(12) NOT NULL DEFAULT '',
  `re_bankcard` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='提现';
CREATE TABLE `sky_mod_fxa_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL,
  `money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '账号余额',
  `total_money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '总收入',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_fxa_ushare` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `productid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '产品',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商家',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '数量',
  PRIMARY KEY (`id`),
  KEY `productid` (`productid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='商家';

eof;
?>