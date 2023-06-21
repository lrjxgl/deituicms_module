<?php
function __moduleInit(){
	C()->smarty->$template_dir="module/pdd/themes/baicha/";
	C()->smarty->assign(array(
		"skins"=>"module/pdd/themes/baicha/"
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
	 
	if(!isset($_SESSION["mpdd_shop_admin"])){
		if(!in_array(get("m"),array("pdd_login"))){
			header("Location:/moduleshop.php?m=pdd_login");
			exit;
		}
		
	}
	
	define("SHOPID",$_SESSION["mpdd_shop_admin"]["shopid"]);
}
?>