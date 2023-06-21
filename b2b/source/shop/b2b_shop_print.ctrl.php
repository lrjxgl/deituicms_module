<?php
class b2b_shop_printControl extends skymvc{
	
	public function onDefault(){
		$_SESSION["ss_printer_shoptable"]="b2b_shop";
		$_SESSION["ss_printer_shopid"]=SHOPID;
		$_SESSION["ss_printer_backshop"]="/moduleshop?m=b2b";
		header("Location: /moduleshop.php?m=printer");
	}
}
?>