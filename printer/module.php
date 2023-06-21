<?php
define("PRINTER_MODEL_ID",2);
define("MODULENAME","printer");
function moduleShopInit(){
	if(empty($_SESSION["ss_printer_shoptable"]) || empty($_SESSION["ss_printer_shopid"])){
		exit("权限错误");
	}
	define("SHOPTABLE",$_SESSION["ss_printer_shoptable"]);
	define("SHOPID",$_SESSION["ss_printer_shopid"]);
	define("BACKSHOP",$_SESSION["ss_printer_backshop"]);
}
?>