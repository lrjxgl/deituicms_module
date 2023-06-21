<?php
function moduleInit(){
	if(!isset($_SESSION["ssuser"])){
		if((isset($_COOKIE['authcode']) or get_post("loginToken") or get_post('authcode') ) && get('m')!="login"){
			M('login')->CodeLogin();
		}else{
			if(INWEIXIN){
				$backurl=urlencode(HTTP_HOST.$_SERVER["REQUEST_URI"]);
				header("Location: /index.php?m=open_weixin&a=Geturl&backurl=".$backurl);
				exit;
			}
		}
	}
}

function senderInit(){
	if(!isset($_SESSION["mhousehold_sender"])){
		$unLogin=true;
		if(MM("household","household_sender")->codeLogin()){
			$unLogin=false;
		} 
		if($unLogin && !in_array(get("m"),array("household_login"))){
			if(get("ajax")){
				if(!MM("household","household_login")->codeLogin()){
					echo json_encode(array(
						"message"=>"请先登录",
						"error"=>1000
					));
					exit;
				}
				
				
			}else{
				header("Location:/sender.php?m=household_login");
				exit;
			}
			
		}
		
	}
	define("SENDERID",intval($_SESSION["mhousehold_sender"]));
}
?>