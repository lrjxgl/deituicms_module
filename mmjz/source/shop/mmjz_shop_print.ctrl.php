<?php
class mmjz_shop_printControl extends skymvc{
	
	public function onDefault(){
		$_SESSION["ss_printer_shoptable"]="mmjz_shop";
		$_SESSION["ss_printer_shopid"]=SHOPID;
		$_SESSION["ss_printer_backshop"]="/moduleshop?m=mmjz";
		header("Location: /moduleshop.php?m=printer");
	}
}
?>