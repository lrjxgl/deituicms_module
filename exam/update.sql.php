<?php
$content=<<<eof
ALTER TABLE `sky_mod_exam`
ADD COLUMN `isone`  tinyint UNSIGNED NOT NULL DEFAULT 0 AFTER `isonline`;
eof;
?>