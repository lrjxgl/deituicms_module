<?php
$content=<<<eof
CREATE TABLE `sky_mod_collect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(225) CHARACTER SET utf8 DEFAULT NULL,
  `url` varchar(225) CHARACTER SET utf8 DEFAULT NULL,
  `rule_id` int(11) NOT NULL DEFAULT '0',
  `dateline` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `isvalid` tinyint(4) NOT NULL DEFAULT '0',
  `ruledata` mediumtext CHARACTER SET utf8,
  PRIMARY KEY (`id`),
  KEY `rule_id` (`rule_id`) USING BTREE,
  KEY `url` (`url`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_collect_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pi_user` mediumtext CHARACTER SET utf8,
  `pi_content` mediumtext CHARACTER SET utf8,
  `pwd` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `online_num` int(11) DEFAULT '0',
  `pi_cron` text CHARACTER SET utf8,
  `isproxy` tinyint(3) unsigned DEFAULT '0' COMMENT '代理',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_collect_ip` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'ip',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `checktime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '检测时间',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_collect_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(225) CHARACTER SET utf8 NOT NULL,
  `dateline` int(11) NOT NULL,
  `iswap` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '手机端',
  `iscurl` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `type_id` tinyint(4) NOT NULL DEFAULT '0',
  `domain` varchar(225) CHARACTER SET utf8 NOT NULL,
  `list_url` varchar(225) CHARACTER SET utf8 NOT NULL,
  `list_rule` text CHARACTER SET utf8 NOT NULL,
  `content_url` varchar(225) CHARACTER SET utf8 NOT NULL,
  `content_rule` text CHARACTER SET utf8 NOT NULL,
  `start_page` int(11) NOT NULL DEFAULT '0',
  `end_page` int(11) NOT NULL DEFAULT '10',
  `now_page` int(11) DEFAULT '0',
  `pagesize` int(11) DEFAULT '1',
  `page_content` text CHARACTER SET utf8 NOT NULL,
  `catid` int(11) NOT NULL DEFAULT '0',
  `page_url` varchar(225) CHARACTER SET utf8 NOT NULL,
  `dl_url` varchar(225) CHARACTER SET utf8 DEFAULT NULL,
  `filter_img` tinyint(4) DEFAULT '0',
  `shopid` int(11) DEFAULT '0',
  `remote_img` tinyint(4) NOT NULL DEFAULT '0',
  `mdname` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '模块',
  PRIMARY KEY (`id`),
  KEY `domain` (`domain`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_collect_type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `orderindex` tinyint(4) DEFAULT '0',
  `status` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

eof;
?>