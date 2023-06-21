<?php
class pdd_shop_moneyControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$shop=M("mod_pdd_shop")->selectRow("shopid=".SHOPID);
		$bankList=M("mod_pdd_bankcard")->select(array(
			"where"=>"shopid=".SHOPID
		));
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"bankList"=>$bankList
		));
		$this->smarty->display("pdd_shop_money/index.html");
	}
	
}