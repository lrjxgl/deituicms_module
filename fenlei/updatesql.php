<?php
$updateVersion=4.1;
$updateTime="2012-01-17 18:00:01";
$content=<<<eof
ALTER TABLE `sky_mod_fenlei` ADD COLUMN `offtime`  int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '下架时间' AFTER `hb_on`;
ALTER TABLE `sky_mod_fenlei_category` ADD COLUMN `update_money`  decimal(6,2) UNSIGNED NOT NULL DEFAULT 0.00 AFTER `comment_open`;
ALTER TABLE `sky_mod_fenlei_category` ADD COLUMN `on_day`  int(10) UNSIGNED NOT NULL DEFAULT 7 COMMENT '在线天数' AFTER `update_money`;
ALTER TABLE `sky_mod_fenlei_config` ADD COLUMN `update_money`  decimal(6,2) UNSIGNED NOT NULL DEFAULT 0.00 AFTER `hb_wx_follow`;
eof;
?>