<?php
class csc_kefuControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$shopid=get("shopid","i");
		$shop=MM("csc","csc_shop")->get($shopid);
		$this->smarty->goAssign(array(
			"shop"=>$shop
		));
		$this->smarty->display("csc_kefu/index.html");
	}
	
}