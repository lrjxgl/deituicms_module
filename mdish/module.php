<?php
	function moduleInit(){
		C()->smarty->template_dir="module/mdish/themes/index/";
		C()->smarty->assign(array(
			"skins"=>"module/mdish/themes/index/"
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
					$backurl=HTTP_HOST.$_SERVER['REQUEST_URI'];
				}
				if(preg_match("/login/i",$backurl)){
					$backurl="/index.php";
				} 
				
				header("Location: /index.php?m=open_weixin&a=Geturl&backurl=".urlencode($backurl));
				exit;	 
			}		 
		}
	}
?>