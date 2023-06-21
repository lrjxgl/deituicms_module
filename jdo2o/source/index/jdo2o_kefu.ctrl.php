<?php
class jdo2o_kefuControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$shopid=get("shopid","i");
		$shop=MM("jdo2o","jdo2o_shop")->get($shopid);
		$this->smarty->goAssign(array(
			"shop"=>$shop
		));
		$this->smarty->display("jdo2o_kefu/index.html");
	}
	
}