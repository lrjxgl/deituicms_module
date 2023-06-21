<?php
	function moduleInit(){
		$teamid=get("s2c_teamid","i");
		if(!$teamid){
			$teamid=intval($_COOKIE["s2c_teamid"]);
			if(!$teamid){
				$team=M("mod_s2c_team")->selectRow("status=1 ");
				if(!$team){
					$teamid=M("mod_s2c_team")->insert(array(
						"nickname"=>"测试",
						"title"=>"测试团队",
						"status"=>1
					));
					 
					
				}else{
					$teamid=$team["teamid"];
				}
				setCookie("s2c_teamid",$teamid,time()+3600*24*1000);
			}
		}
		define("TEAMID",$teamid);
	 
		
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
?>