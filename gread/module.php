<?php
 
function moduleShopInit(){
	 
	if(!isset($_SESSION['ssgreadshop'])){
		if(get("m")!="gread_login"){
			C()->goAll("请登录",1000,0,"/moduleshop.php?m=gread_login");
		}
		define("SHOPID",0);
	}else{
		  
		define("SHOPID",intval($_SESSION['ssgreadshop']["shopid"]));
		 
	}
}
?>