<?php
//折扣返券
define("FLK_DISCOUNT",0.1);
function moduleInit(){
	 
	C()->smarty->template_dir="module/flk/themes/index/";
	C()->smarty->assign(array(
		"skins"=>"module/flk/themes/index/"
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
	if(get("set_invite_orderid")){
		$_SESSION["ss_invite_orderid"]=get("set_invite_orderid","i");
		
	}
	 
}
function moduleShopInit(){
	 
	if(!isset($_SESSION["mflk_shop_admin"])){
		if(!in_array(get("m"),array("flk_login"))){
			if(get("ajax")){
				if(!MM("flk","flk_login")->codeLogin()){
					echo json_encode(array(
						"message"=>"请先登录",
						"error"=>1000
					));
					exit;
				}
				
				
			}else{
				header("Location:/moduleshop.php?m=flk_login");
				exit;
			}
			
		}
		
	}
	
	define("SHOPID",$_SESSION["mflk_shop_admin"]["shopid"]);
}
?>