<?php
	function moduleInit(){
		$teamid=get_post("teamid","i");
		if($teamid){
			setcookie("f2c_teamid",$teamid,time()+36000000);
		}else{
			$teamid=intval($_COOKIE["f2c_teamid"]);
			if(!$teamid){
				$teamid=1;
			}
		}
		
		define("TEAMID",$teamid);
		if(isset($_SESSION['ssuser']['userid'])){
			C()->ssuser=$_SESSION['ssuser'];//当前登录用户的信息
			C()->smarty->assign("ssuser",C()->ssuser);
		}else{
			//存在登录码
			if((isset($_COOKIE['authcode']) or get_post("loginToken") or get_post('authcode') ) && get('m')!="login"){
				
				M('login')->CodeLogin();
			}		 
		}
		
	}
?>