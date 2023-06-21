<?php
require "api.php";
$app_key="09043edbf956585cf21643d4f7ae29b8";
$app_secret="56a52f5e3af4473bb317010eede95003";
$skuid="5225346";
$JdApi = new JdApi($app_key,$app_secret);
$goodsReq=array(
	"eliteId"=>22
);
$res=$JdApi->jingfenQuery($goodsReq);
print_r($res);