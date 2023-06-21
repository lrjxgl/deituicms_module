<?php
$content=<<<eof
CREATE TABLE `sky_mod_tts_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `com` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='tts设置';
CREATE TABLE `sky_mod_tts_open` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `com` varchar(32) NOT NULL DEFAULT '',
  `appid` varchar(32) NOT NULL DEFAULT '',
  `secret_id` varchar(64) NOT NULL DEFAULT '',
  `secret_key` varchar(64) NOT NULL DEFAULT '',
  `attr1` varchar(64) NOT NULL DEFAULT '',
  `attr2` varchar(64) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `create_time` datetime NOT NULL DEFAULT '2023-05-02 06:02:01',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='tts开放平台接入';

eof;
?>