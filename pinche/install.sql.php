<?php
$content=<<<eof
CREATE TABLE `sky_mod_pinche_bankcard` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `driverid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `yhk_name` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '银行名称',
  `yhk_haoma` varchar(24) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '银行卡号码',
  `yhk_huming` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '银行卡户名',
  `telephone` varchar(18) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '电话',
  `yhk_address` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '开户地址',
  `paytype` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '支付方式  支付宝 银行卡 微信 ',
  PRIMARY KEY (`id`),
  KEY `driverid` (`driverid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pinche_baoche` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `start_addr` varchar(225) NOT NULL DEFAULT '' COMMENT '开始',
  `content` varchar(225) NOT NULL DEFAULT '' COMMENT '详情',
  `createtime` datetime NOT NULL DEFAULT '2022-02-25 08:01:01',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `money` decimal(9,2) NOT NULL DEFAULT '0.00',
  `ispay` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `recharge_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='包车';
CREATE TABLE `sky_mod_pinche_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_per_money` tinyint(4) NOT NULL DEFAULT '0' COMMENT '站点抽成',
  `invite_per_money` tinyint(4) NOT NULL DEFAULT '0' COMMENT '邀请抽成',
  `bai_time` varchar(16) NOT NULL DEFAULT '',
  `retype` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付方式 0后支付 1预支付',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_pinche_dache_order` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `telephone` varchar(15) NOT NULL DEFAULT '',
  `from_addr` varchar(64) NOT NULL DEFAULT '' COMMENT '出发地',
  `to_addr` varchar(64) NOT NULL DEFAULT '' COMMENT '目的地',
  `user_num` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '乘客数',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '介绍',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `ispay` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付',
  `createtime` datetime NOT NULL DEFAULT '2022-08-19 08:53:01' COMMENT '床架时间',
  `order_time` datetime NOT NULL DEFAULT '2022-08-19 08:53:01' COMMENT '接单时间',
  `driverid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '司机',
  PRIMARY KEY (`orderid`),
  KEY `userid` (`userid`),
  KEY `senderid` (`driverid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='打车订单';
CREATE TABLE `sky_mod_pinche_driver` (
  `driverid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `truename` varchar(12) NOT NULL DEFAULT '' COMMENT '昵称',
  `userhead` varchar(225) NOT NULL DEFAULT '' COMMENT '头像',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '介绍',
  `carno` varchar(12) NOT NULL DEFAULT '' COMMENT '车牌号',
  `carpic` varchar(225) NOT NULL DEFAULT '' COMMENT '车辆图片',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `isfree` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '空闲',
  `bzmoney` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '保证金',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `telephone` varchar(18) NOT NULL DEFAULT '',
  `address` varchar(64) NOT NULL DEFAULT '',
  `userno` varchar(20) NOT NULL DEFAULT '' COMMENT '身份证号码',
  `createtime` datetime NOT NULL DEFAULT '2022-07-12 10:23:01',
  PRIMARY KEY (`driverid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pinche_driver_account` (
  `driverid` int(10) unsigned NOT NULL,
  `total_money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '总收入',
  `balance_money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '账户余额',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`driverid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_pinche_driver_account_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dateline` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  `driverid` int(11) NOT NULL DEFAULT '0' COMMENT '司机',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00',
  `typeid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_pinche_driver_apply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `truename` varchar(12) NOT NULL DEFAULT '' COMMENT '昵称',
  `userhead` varchar(225) NOT NULL DEFAULT '' COMMENT '头像',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '介绍',
  `carno` varchar(12) NOT NULL DEFAULT '' COMMENT '车牌号',
  `carpic` varchar(225) NOT NULL DEFAULT '' COMMENT '车辆图片',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `userno` varchar(22) NOT NULL DEFAULT '' COMMENT '身份证号码',
  `usercarda` varchar(225) NOT NULL DEFAULT '' COMMENT '身份证正面',
  `usercardb` varchar(225) NOT NULL DEFAULT '' COMMENT '身份证反面',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `telephone` varchar(18) NOT NULL DEFAULT '',
  `driverpic` varchar(225) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `address` varchar(225) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pinche_driver_auth` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `driverid` int(10) unsigned NOT NULL DEFAULT '0',
  `userno` varchar(22) NOT NULL DEFAULT '' COMMENT '身份证号码',
  `usercarda` varchar(225) NOT NULL DEFAULT '' COMMENT '身份证正面',
  `usercardb` varchar(225) NOT NULL DEFAULT '' COMMENT '身份证反面',
  `carno` varchar(12) NOT NULL DEFAULT '' COMMENT '车牌号码',
  `carpic` varchar(225) NOT NULL DEFAULT '' COMMENT '车的照片',
  `createtime` varchar(225) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `driverpic` varchar(225) NOT NULL DEFAULT '' COMMENT '驾驶证',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pinche_driver_line` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `driverid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '司机',
  `lineid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '线路',
  `freetime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `driverid` (`driverid`) USING BTREE,
  KEY `lineid` (`lineid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pinche_group` (
  `gid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `driverid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '司机',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `usernum` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '乘客人数',
  `freenum` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `money` int(10) unsigned NOT NULL DEFAULT '0',
  `ptime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '成团时间',
  `lineid` int(10) unsigned NOT NULL DEFAULT '0',
  `wait_etime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后时间',
  PRIMARY KEY (`gid`),
  KEY `driverid` (`driverid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pinche_group_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `role` varchar(15) NOT NULL DEFAULT '' COMMENT '角色',
  `roleid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `action` varchar(15) NOT NULL DEFAULT '' COMMENT '动作',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_pinche_group_msg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `driverid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) NOT NULL DEFAULT '',
  `gid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pinche_line` (
  `lineid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL DEFAULT '' COMMENT '路线名称',
  `basemoney` smallint(10) unsigned NOT NULL DEFAULT '0',
  `hei_money` smallint(5) unsigned NOT NULL DEFAULT '0',
  `bai_money` smallint(5) unsigned NOT NULL DEFAULT '0',
  `start_addr` varchar(12) NOT NULL DEFAULT '' COMMENT '开始地址',
  `start_lat` decimal(9,6) NOT NULL DEFAULT '0.000000' COMMENT '开始纬度',
  `start_lng` decimal(9,6) NOT NULL DEFAULT '0.000000' COMMENT '开始精度',
  `sendmoney` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '接车费用 3/千米',
  `end_addr` varchar(12) NOT NULL DEFAULT '' COMMENT '结束地址',
  `end_lat` decimal(9,6) NOT NULL DEFAULT '0.000000' COMMENT '结束纬度',
  `end_lng` decimal(9,6) NOT NULL DEFAULT '0.000000' COMMENT '结束精度',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '线路图图片',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '描述',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `wait_time` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '等待时间 分钟',
  `cityid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '城市',
  `send_paytype` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0需按人数算 1只按位置',
  PRIMARY KEY (`lineid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pinche_line_addr` (
  `addrid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lineid` int(10) unsigned NOT NULL DEFAULT '0',
  `addr` varchar(12) NOT NULL DEFAULT '' COMMENT '地址',
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000' COMMENT '纬度',
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000' COMMENT '精度',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `stype` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '类型 1七点 2终点',
  `price` decimal(5,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '价格',
  PRIMARY KEY (`addrid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pinche_order` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下单人',
  `invite_userid` int(10) unsigned NOT NULL DEFAULT '0',
  `money` decimal(7,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '金额',
  `ispay` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付',
  `retype` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付模式 0后支付 1预支付',
  `usernum` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '人数',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `driverid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '司机',
  `gid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '成团',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `start_addr` varchar(64) NOT NULL DEFAULT '',
  `start_addrid` int(10) unsigned NOT NULL DEFAULT '0',
  `start_lat` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `start_lng` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `end_addr` varchar(64) NOT NULL DEFAULT '',
  `end_addrid` int(10) unsigned NOT NULL DEFAULT '0',
  `lineid` int(10) unsigned NOT NULL DEFAULT '0',
  `end_lat` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `end_lng` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `wait_etime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最晚上车时间',
  `nickname` varchar(15) NOT NULL DEFAULT '',
  `telephone` varchar(15) NOT NULL DEFAULT '',
  `recharge_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`orderid`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `driverid` (`driverid`) USING BTREE,
  KEY `gid` (`gid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_pinche_order_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `role` varchar(15) NOT NULL DEFAULT '' COMMENT '角色',
  `roleid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `action` varchar(15) NOT NULL DEFAULT '' COMMENT '动作',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` varchar(225) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_pinche_people` (
  `ppid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `nickname` varchar(15) NOT NULL DEFAULT '',
  `telephone` varchar(15) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ppid`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

eof;
?>