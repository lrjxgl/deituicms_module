<?php
class gxny_shop_moneyControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$shop=M("mod_gxny_shop")->selectRow("shopid=".SHOPID);
		$shopmoney=M("mod_gxny_shop_money")->selectRow("shopid=".SHOPID);
		$bankList=M("mod_gxny_bankcard")->select(array(
			"where"=>"shopid=".SHOPID
		));
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"shopmoney"=>$shopmoney,
			"bankList"=>$bankList
		));
		$this->smarty->display("gxny_shop_money/index.html");
	}
	
}