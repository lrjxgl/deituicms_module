<?php
class pdd_kefuControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$shopid=get("shopid","i");
		$shop=MM("pdd","pdd_shop")->get($shopid);
		$this->smarty->goAssign(array(
			"shop"=>$shop
		));
		$this->smarty->display("pdd_kefu/index.html");
	}
	
}