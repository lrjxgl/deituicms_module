<?php
$content=<<<eof
CREATE TABLE `sky_mod_checkin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siteid` int(11) NOT NULL DEFAULT '1',
  `userid` int(11) unsigned NOT NULL DEFAULT '0',
  `day` int(11) NOT NULL DEFAULT '0',
  `dateline` int(11) NOT NULL DEFAULT '0',
  `mood` tinyint(4) NOT NULL DEFAULT '1',
  `content` varchar(225) NOT NULL DEFAULT '',
  `type_id` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(32) CHARACTER SET utf8 NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `grade` smallint(5) unsigned NOT NULL DEFAULT '0',
  `gold` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `t_u_day` (`type_id`,`userid`,`day`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `sky_mod_checkin_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `grade` smallint(5) unsigned NOT NULL DEFAULT '0',
  `gold` smallint(5) unsigned NOT NULL DEFAULT '0',
  `max_grade` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '签到最大值',
  `max_gold` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '签到最大值',
  `day_grade_p` tinyint(4) NOT NULL DEFAULT '0' COMMENT '连续签到附加',
  `day_gold_p` tinyint(4) NOT NULL DEFAULT '0' COMMENT '连续签到附加',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '签到说明',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='签到配置';
CREATE TABLE `sky_mod_checkin_index` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '签到类型 1圈子签到',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间',
  `grade` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '积分 连续签到几次',
  `last_day` int(11) NOT NULL DEFAULT '0',
  `last_ip` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '签到ip',
  `all_times` mediumint(8) unsigned NOT NULL DEFAULT '1' COMMENT '签到总次数',
  `is_valid` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1有效 2无效',
  `gold` int(11) NOT NULL DEFAULT '1' COMMENT '金币',
  `days` int(11) NOT NULL DEFAULT '1' COMMENT '连续签到次数',
  `siteid` int(11) NOT NULL DEFAULT '1' COMMENT '分站id',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`,`type_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

eof;
?>