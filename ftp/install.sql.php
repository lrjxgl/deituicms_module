<?php
$content=<<<eof
CREATE TABLE `sky_mod_ftp_host` (
  `ftpid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(32) NOT NULL DEFAULT '',
  `ftpdata` varchar(1024) NOT NULL DEFAULT '',
  PRIMARY KEY (`ftpid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='ftp';

eof;
?>