<?php
function moduleInit(){
	if(get("shopid")){
		define("SHOPID",get("shopid","i"));
	}else{
		define("SHOPID",1);
	}
	
}
?>