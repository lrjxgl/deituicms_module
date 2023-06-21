<?php
function moduleShopInit(){
	$userid=M("login")->userid;
	if(!$userid){
		M("login")->codeLogin();
		$userid=M("login")->userid;
	}
	if(!$userid){	
		C()->goAll("暂无权限",1);
	}
	$shop=M("mod_recycle_shop")->selectRow(array(
		"where"=>" userid=".$userid,
		"fields"=>"shopid,title"
	));
	if(empty($shop)){
		C()->goAll("暂无权限",1);
	}
	define("SHOPID",$shop["shopid"]);
}
?>