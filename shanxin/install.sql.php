<?php
$content=<<<eof
CREATE TABLE `sky_mod_shanxin` (
  `sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '名称',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '描述',
  `imgurl` varchar(225) NOT NULL DEFAULT '',
  `stime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `etime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '截止时间',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看人数',
  `join_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '参与人数',
  `finish_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '完成人数',
  `juan_num` int(10) unsigned NOT NULL DEFAULT '0',
  `money` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `content` text,
  `product` varchar(225) DEFAULT '',
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='善心汇';
CREATE TABLE `sky_mod_shanxin_bang` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '描述',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '数量',
  `money` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `ispay` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付',
  `recharge_id` varchar(32) NOT NULL DEFAULT '',
  `paytype` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `sid` (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='善心汇-参与者';
CREATE TABLE `sky_mod_shanxin_join` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '照片',
  `nickname` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '电话',
  `address` varchar(128) NOT NULL DEFAULT '' COMMENT '地址',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `description` varchar(225) NOT NULL DEFAULT '' COMMENT '描述',
  `p_imgurl` varchar(225) NOT NULL DEFAULT '' COMMENT '回访照片',
  `p_desc` varchar(225) NOT NULL DEFAULT '' COMMENT '回访描述',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `p_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '回访时间',
  PRIMARY KEY (`id`),
  KEY `sid` (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='善心汇-参与者';

eof;
?>