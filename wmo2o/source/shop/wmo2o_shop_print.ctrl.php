<?php
class wmo2o_shop_printControl extends skymvc{
	
	public function onDefault(){
		$_SESSION["ss_printer_shoptable"]="wmo2o_shop";
		$_SESSION["ss_printer_shopid"]=SHOPID;
		$_SESSION["ss_printer_backshop"]="/moduleshop?m=wmo2o";
		header("Location: /moduleshop.php?m=printer");
	}
}
?>