<?php

function moduleShopInit(){
	if(ISWAP || 1==1){
		C()->smarty->template_dir="module/exue/themes/shop/wap";
		C()->smarty->assign(array(
			"skins"=>"module/exue/themes/shop/wap/"
		)); 
	}else{
		C()->smarty->template_dir="module/exue/themes/shop/pc";
		C()->smarty->assign(array(
			"skins"=>"module/exue/themes/shop/pc/"
		)); 
	} 
	if(!isset($_SESSION["mexue_shop_admin"])){
		if(!in_array(get("m"),array("exue_login"))){
			if(get("ajax")){
				if(!MM("exue","exue_login")->codeLogin()){
					echo json_encode(array(
						"message"=>"请先登录",
						"error"=>1000
					));
					exit;
				}
				
				
			}else{
				header("Location:/moduleshop.php?m=exue_login");
				exit;
			}
			
		}
		
	}
	
	define("SHOPID",$_SESSION["mexue_shop_admin"]["shopid"]);
}
?>