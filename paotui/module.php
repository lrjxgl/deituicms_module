<?php
function senderInit(){
	if(!isset($_SESSION["mpaotui_sender"])){
		$unLogin=true;
		if(MM("paotui","paotui_sender")->codeLogin()){
			$unLogin=false;
		} 
		if($unLogin && !in_array(get("m"),array("paotui_login"))){
			if(get("ajax")){
				if(!MM("paotui","paotui_login")->codeLogin()){
					echo json_encode(array(
						"message"=>"请先登录",
						"error"=>1000
					));
					exit;
				}
				
				
			}else{
				header("Location:/sender.php?m=paotui_login");
				exit;
			}
			
		}
		
	}
	define("SENDERID",intval($_SESSION["mpaotui_sender"]));
}
?>