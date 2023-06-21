<?php
	function moduleInit(){
		
		$config=M("mod_cy2c_config")->selectRow("1");
		
		if(isset($config["shoptype"])){
			define("MCY2C_SHOPTYPE",$config["shoptype"]);
		}else{
			define("MCY2C_SHOPTYPE","");
		}
		if(isset($_SESSION["mcy2c_placeid"])){
			define("PLACEID",intval($_SESSION["mcy2c_placeid"]));
		}elseif(get_post("placeid")){
			
			define("PLACEID",get_post("placeid","i")); 
			$_SESSION["mcy2c_placeid"]=PLACEID;
		}else{
			define("PLACEID",0);
		}
		
		if(!isset($_SESSION["ssuser"])){
			if((isset($_COOKIE['authcode']) or get_post("loginToken") or get_post('authcode') ) && get('m')!="login"){
				M('login')->CodeLogin();
			}
		}
	}
	
?>