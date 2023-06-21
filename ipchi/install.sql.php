<?php
$content=<<<eof
CREATE TABLE `sky_mod_ipchi_ip` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(64) NOT NULL DEFAULT '',
  `port` varchar(6) NOT NULL DEFAULT '8080',
  `protocol` varchar(12) NOT NULL DEFAULT '',
  `createtime` datetime NOT NULL DEFAULT '2022-11-27 10:01:01',
  `updatetime` datetime NOT NULL DEFAULT '2022-11-27 10:01:01',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `area` varchar(16) NOT NULL DEFAULT '',
  `usetime` datetime NOT NULL DEFAULT '2022-11-27 10:01:01',
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`)
) ENGINE=InnoDB AUTO_INCREMENT=952 DEFAULT CHARSET=utf8 COMMENT='ip库';

eof;
?>