<?php
define("BOOK_USER_PER",0.5);
function moduleInit(){
	$shopid=0;
	if(isset($_GET["shopid"])){
		$shopid=get("shopid","i");
	}
	
	if(!$shopid){
		$shopid=intval($_COOKIE["ck_olprint_shopid"]);
	}
	if(!isset($_SESSION["ssuser"])){
		if((isset($_COOKIE['authcode']) or get_post("loginToken")  or get_post('authcode') ) && get('m')!="login"){
			M('login')->CodeLogin();
		}elseif(get('m')!='open_weixin' && get('m')!="login" && INWEIXIN && get('m')!="checkcode"){
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
	define("SHOPID",$shopid);
}

function moduleShopInit(){
	if(ISWAP  ){
		C()->smarty->template_dir="module/olprint/themes/shop/wap";
		C()->smarty->assign(array(
			"skins"=>"module/olprint/themes/shop/wap/"
		)); 
	}else{
		C()->smarty->template_dir="module/olprint/themes/shop/pc";
		C()->smarty->assign(array(
			"skins"=>"module/olprint/themes/shop/pc/"
		)); 
	} 
	if(!isset($_SESSION["molprint_shop_admin"])){
		if(!in_array(get("m"),array("olprint_login"))){
			if(get("ajax")){
				if(!MM("olprint","olprint_login")->codeLogin()){
					echo json_encode(array(
						"message"=>"请先登录",
						"error"=>1000
					));
					exit;
				}
				
				
			}else{
				header("Location:/moduleshop.php?m=olprint_login");
				exit;
			}
			
		}
		
	}
	
	define("SHOPID",$_SESSION["molprint_shop_admin"]["shopid"]);
	define("SITEID",$_SESSION["molprint_shop_admin"]["siteid"]);
}

function moduleAdminInit(){
	define("SITEWHERE",'');
	$m=get("m",'h');
	if(!in_array($m,array('login'))){
		if(!isset($_SESSION['ssadmin']['id'])){
			C()->goAll("请先登录",0,0,"/admin.php?m=login");
		}
		$access=m("admin_group")->selectOne(array(
			"where"=>"id=".$_SESSION['ssadmin']['group_id'],
			"fields"=>"content"
		));
		$permission=unserialize($access);
		if(!C()->checkpermission($permission) && !$_SESSION['ssadmin']['isfounder'] ){
			//exit("您无权限");	
		}
	}	 
}

?>