<?php
function moduleInit(){
	C()->smarty->template_dir="module/pinche/themes/index/";
	C()->smarty->assign(array(
		"skins"=>"module/pinche/themes/index/"
	));
	if(isset($_SESSION['ssuser']['userid'])){
		C()->ssuser=$_SESSION['ssuser'];//当前登录用户的信息
		C()->smarty->assign("ssuser",C()->ssuser);
	}else{
		//存在登录码
		if((isset($_COOKIE['authcode']) or get_post('authcode') ) && get('m')!="login"){
			
			M('login')->CodeLogin();
		}elseif(get('m')!='wxlogin' && get('m')!="login" && INWEIXIN && get('m')!="checkcode"){
			$backurl=get_post('backurl','x');
			if(!$backurl){
				$backurl='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			}
			if(preg_match("/login/i",$backurl)){
				$backurl="/index.php";
			} 
			
			header("Location: /index.php?m=open_weixin&a=Geturl&backurl=".urlencode($backurl));
			exit;	 
		}			 
	}
	 
}
function moduleShopInit(){
	 
	if(!isset($_SESSION["ss_pinche_driverid"])){
		$unLogin=true;
		if(MM("pinche","pinche_driver")->codeLogin()){
			$unLogin=false;
		} 
		if($unLogin && !in_array(get("m"),array("pinche_login"))){
			if(get("ajax")){
				if(!MM("pinche","pinche_driver")->codeLogin()){
					echo json_encode(array(
						"message"=>"请先登录",
						"error"=>1000
					));
					exit;
				}
				
				
			}else{
				header("Location:/moduleshop.php?m=pinche_login");
				exit;
			}
			
		}
		
	}
	define("DRIVERID",intval($_SESSION["ss_pinche_driverid"]));
}

?>