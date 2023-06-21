<?php
$content=<<<eof
CREATE TABLE `sky_mod_printer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tablename` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '表名',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '打印机名称',
  `printer_phone` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '手机',
  `printer_host` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '服务器',
  `printer_port` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '密码',
  `printer_sn` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '用户',
  `printer_token` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '秘钥',
  `tpl` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT 'default' COMMENT '模板',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '加入时间',
  `bstatus` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `pcom` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '打印机公司',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_printer_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `feier_user` varchar(32) NOT NULL DEFAULT '' COMMENT '飞鹅user',
  `feier_ukey` varchar(32) NOT NULL DEFAULT '' COMMENT '飞鹅ukey',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
CREATE TABLE `sky_mod_printer_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tablename` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '表名',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店铺',
  `title` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '打印主题',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '加入时间',
  `last_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后更新时间',
  `bstatus` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `times` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '打印次数',
  `ftable` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text CHARACTER SET utf8 NOT NULL COMMENT '打印内容',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`,`tablename`,`dateline`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_printer_plan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tablename` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '表名',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店铺',
  `title` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '打印主题',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '加入时间',
  `last_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后更新时间',
  `bstatus` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `times` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '打印次数',
  `ftable` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text CHARACTER SET utf8 NOT NULL COMMENT '打印内容',
  `isvalid` tinyint(4) NOT NULL DEFAULT '0' COMMENT ' 0无效 1有效 2成功  4锁住',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`,`tablename`,`dateline`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_printer_tpl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tablename` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '表名',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '加入时间',
  `shead` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `sfoot` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `sqr` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `num` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '打印份数',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

eof;
?>