<?php
function moduleShopInit(){
	$userid=M("login")->userid;
	$shop=MM("youyao","youyao_shop")->getShopByUserid($userid);
	if(!empty($shop)){
		define("SHOPID",$shop["shopid"]);
	}else{
		C()->goAll("暂无权限",1,0,"/module.php?m=youyao");
	}
	
}
?>