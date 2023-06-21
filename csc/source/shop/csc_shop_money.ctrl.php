<?php
class csc_shop_moneyControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$shop=M("mod_csc_shop")->selectRow("shopid=".SHOPID);
		$shopmoney=M("mod_csc_shop_money")->selectRow("shopid=".SHOPID);
		$list=M("mod_csc_shop_money_log")->select(array(
			"where"=>"shopid=".SHOPID,
			"order"=>"id DESC",
			"limit"=>48
		)); 
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"shopmoney"=>$shopmoney,
			"list"=>$list
		));
		$this->smarty->display("csc_shop_money/index.html");
	}
	
}