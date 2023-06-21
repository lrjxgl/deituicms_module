<?php
$content=<<<eof
CREATE TABLE `sky_mod_kuaidi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `kdhao` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '快递号',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

eof;
?>