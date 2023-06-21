<?php
$content=<<<eof
CREATE TABLE `sky_mod_photo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '用户',
  `catid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类',
  `title` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `description` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `address` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '地址',
  `createtime` datetime NOT NULL DEFAULT '2018-12-07 11:32:01',
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imgurl` varchar(225) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '图片',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_num` int(10) unsigned NOT NULL DEFAULT '0',
  `love_num` int(10) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext CHARACTER SET utf8,
  `mapCanvas` text CHARACTER SET utf8,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

eof;
?>