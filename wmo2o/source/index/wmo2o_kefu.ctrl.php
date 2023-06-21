<?php
class wmo2o_kefuControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$shopid=get("shopid","i");
		$shop=MM("wmo2o","wmo2o_shop")->get($shopid);
		$this->smarty->goAssign(array(
			"shop"=>$shop
		));
		$this->smarty->display("wmo2o_kefu/index.html");
	}
	
}