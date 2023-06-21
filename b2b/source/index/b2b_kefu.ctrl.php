<?php
class b2b_kefuControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$shopid=get("shopid","i");
		$shop=MM("b2b","b2b_shop")->get($shopid);
		$this->smarty->goAssign(array(
			"shop"=>$shop
		));
		$this->smarty->display("b2b_kefu/index.html");
	}
	
}