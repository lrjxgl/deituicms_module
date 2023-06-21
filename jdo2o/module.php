<?php

function moduleInit(){
	C()->smarty->template_dir="module/jdo2o/themes/index/";
	C()->smarty->assign(array(
		"skins"=>"module/jdo2o/themes/index/"
	));
	if(isset($_SESSION['ssuser']['userid'])){
		C()->ssuser=$_SESSION['ssuser'];//当前登录用户的信息
		C()->smarty->assign("ssuser",C()->ssuser);
	}else{
		//存在登录码
		if((get_post('authcode') || get("loginToken") || $_COOKIE["authcode"] ) && get('m')!="login"){
			
			M('login')->CodeLogin();
		}elseif(get('m')!='wxlogin' && get('m')!="login" && INWEIXIN && get('m')!="checkcode"){
			$backurl=get_post('backurl','x');
			if(!$backurl){
				$backurl=HTTP_HOST.$_SERVER['REQUEST_URI'];
			}
			if(preg_match("/login/i",$backurl)){
				$backurl="/index.php";
			} 
			
			header("Location: /index.php?m=open_weixin&a=Geturl&backurl=".urlencode($backurl));
			exit;	 
		}			 
	}
	if(get("set_shopid")){
		$shopid=get("set_shopid","i");
		setcookie("set_jdo2o_shopid",$shopid,time()+3600*24*3600);
	}elseif($_COOKIE["set_jdo2o_shopid"]){
		$shopid=intval($_COOKIE["set_jdo2o_shopid"]);
		
	}else{
		$shop=M("mod_jdo2o_shop")->selectRow("status=1");
		$shopid=$shop["shopid"];
		 
	}
	
	define("SHOPID",$shopid);
	 
}

function moduleShopInit(){
	if(ISWAP){
		C()->smarty->template_dir="module/jdo2o/themes/shop/wap";
		C()->smarty->assign(array(
			"skins"=>"module/jdo2o/themes/shop/wap/"
		)); 
	}else{
		C()->smarty->template_dir="module/jdo2o/themes/shop/pc";
		C()->smarty->assign(array(
			"skins"=>"module/jdo2o/themes/shop/pc/"
		)); 
	} 
	if(!isset($_SESSION["mjdo2o_shop_admin"])){
		if(!in_array(get("m"),array("jdo2o_login"))){
			if(get("ajax")){
				if(!MM("jdo2o","jdo2o_login")->codeLogin()){
					echo json_encode(array(
						"message"=>"请先登录",
						"error"=>1000
					));
					exit;
				}
				
				
			}else{
				header("Location:/moduleshop.php?m=jdo2o_login");
				exit;
			}
			
		}
		
	}
	
	define("SHOPID",$_SESSION["mjdo2o_shop_admin"]["shopid"]);
}
?>