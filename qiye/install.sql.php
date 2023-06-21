<?php
$content=<<<eof
CREATE TABLE `sky_mod_qiye_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_catid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '产品',
  `article_catid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='配置';

eof;
?>