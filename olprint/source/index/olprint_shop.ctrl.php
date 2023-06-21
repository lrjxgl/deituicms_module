<?php
class olprint_shopControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$shopid=get("shopid","i");
		$shop=MM("olprint","olprint_shop")->get($shopid,"*");
		$this->smarty->goAssign(array(
			"shop"=>$shop
		));
		$this->smarty->display("olprint_shop/index.html");
	}
	public function onChoice(){
		$shopid=get("shopid","i");
		setcookie("ck_olprint_shopid",$shopid,time()+3600*24*30,"/",DOMAIN);
		$this->goAll("success");
	}
	
}
?>