<?php
function moduleInit(){
	C()->smarty->template_dir="module/csc/themes/index/";
	C()->smarty->assign(array(
		"skins"=>"module/csc/themes/index/"
	));
	if(isset($_SESSION['ssuser']['userid'])){
		C()->ssuser=$_SESSION['ssuser'];//当前登录用户的信息
		C()->smarty->assign("ssuser",C()->ssuser);
	}else{
		//存在登录码
		if((get_post('authcode') || $_COOKIE["authcode"] ) && get('m')!="login"){
			
			M('login')->CodeLogin();
		}elseif(get('m')!='wxlogin' && get('m')!="login" && INWEIXIN && get('m')!="checkcode"){
			$backurl=get_post('backurl','x');
			if(!$backurl){
				$backurl=HTTP_HOST.$_SERVER['REQUEST_URI'];
			}
			if(preg_match("/login/i",$backurl)){
				$backurl="/index.php";
			} 
			
			//header("Location: /index.php?m=open_weixin&a=Geturl&backurl=".urlencode($backurl));
			//exit;	 
		}			 
	}
	if(get("set_shopid")){
		$shopid=get("set_shopid","i");
		setcookie("set_shopid",$shopid,time()+3600*24*3600);
	}elseif($_COOKIE["set_shopid"]){
		$shopid=intval($_COOKIE["set_shopid"]);
	}else{
		$shop=M("mod_csc_shop")->selectRow("status=1");
		if(empty($shop)){
			exit("请先添加一家店");
		}
		$shopid=$shop["shopid"];
	}
	define("SHOPID",$shopid);
}
function moduleShopInit(){
	if(ISWAP){
		C()->smarty->template_dir="module/csc/themes/shop/wap";
		C()->smarty->assign(array(
			"skins"=>"module/csc/themes/shop/wap/"
		)); 
	}else{
		C()->smarty->template_dir="module/csc/themes/shop/pc";
		C()->smarty->assign(array(
			"skins"=>"module/csc/themes/shop/pc/"
		)); 
	}
	
	if(!isset($_SESSION["mcsc_shop_admin"])){
		
		if(!in_array(get("m"),array("csc_login"))){
			header("Location:/moduleshop.php?m=csc_login");
			exit;
		}
		
	}
	define("BZSHOPID",19);
	define("SHOPID",$_SESSION["mcsc_shop_admin"]["shopid"]);
}

function moduleSenderInit(){
	
	if(!isset($_SESSION["mcsc_sender"])){
		$unlogin=true;
		if($token=$_COOKIE["mcsc_sender_token"]){
			$senderid=cache()->get($token);
			if($senderid){
				$sender=M("mod_csc_sender")->selectRow(array(
					"where"=>"senderid=".$senderid,
					"fields"=>"senderid,truename,shopid"
				));
				if($sender["status"]>3){
					$this->goAll("您的账号已被禁了",1);
				}
				$_SESSION["mcsc_sender"]=$sender;
				$token="mcsc_sender_token".md5(time());
				cache()->set($token,$sender["senderid"],240);
				setcookie("mcsc_sender_token",$token,time()+3600*24*10);
				$unlogin=false;
			}		
		}
		if($unlogin && !in_array(get("m"),array("csc_login"))){
			header("Location:/sender.php?m=csc_login");
			exit;
		}
	}
	
	define("SENDERID",$_SESSION["mcsc_sender"]["senderid"]);
	define("SHOPID",$_SESSION["mcsc_sender"]["shopid"]);
}
?>