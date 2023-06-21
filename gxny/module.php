<?php 

function moduleShopInit(){
	 
	if(!isset($_SESSION["mgxny_shop_admin"])){
		if(!in_array(get("m"),array("gxny_login"))){
			header("Location:/moduleshop.php?m=gxny_login");
			exit;
		}
		
	}
	
	define("SHOPID",$_SESSION["mgxny_shop_admin"]["shopid"]);
} 
?>