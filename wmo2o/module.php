<?php
function __moduleInit(){
	C()->smarty->template_dir="module/wmo2o/themes/baicha/";
	C()->smarty->assign(array(
		"skins"=>"module/wmo2o/themes/baicha/"
	));
	if(isset($_SESSION['ssuser']['userid'])){
		C()->ssuser=$_SESSION['ssuser'];//当前登录用户的信息
		C()->smarty->assign("ssuser",C()->ssuser);
	}else{
		//存在登录码
		if((isset($_COOKIE['authcode']) or get_post('authcode') ) && get('m')!="login"){
			
			M('login')->CodeLogin();
		}		 
	}
}
function moduleShopInit(){
	if(ISWAP){
		C()->smarty->template_dir="module/wmo2o/themes/shop/wap";
		C()->smarty->assign(array(
			"skins"=>"module/wmo2o/themes/shop/wap/"
		)); 
	}else{
		C()->smarty->template_dir="module/wmo2o/themes/shop/pc";
		C()->smarty->assign(array(
			"skins"=>"module/wmo2o/themes/shop/pc/"
		)); 
	} 
	if(!isset($_SESSION["mwmo2o_shop_admin"])){
		if(!in_array(get("m"),array("wmo2o_login"))){
			if(get("ajax")){
				if(!MM("wmo2o","wmo2o_login")->codeLogin()){
					echo json_encode(array(
						"message"=>"请先登录",
						"error"=>1000
					));
					exit;
				}
				
				
			}else{
				header("Location:/moduleshop.php?m=wmo2o_login");
				exit;
			}
			
		}
		
	}
	
	define("SHOPID",$_SESSION["mwmo2o_shop_admin"]["shopid"]);
}
?>