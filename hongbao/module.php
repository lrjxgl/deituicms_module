<?php
function moduleInit(){
	if(!isset($_SESSION["ssuser"])){
		if((isset($_COOKIE['authcode']) or get_post('authcode') ) && get('m')!="login"){
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
?>