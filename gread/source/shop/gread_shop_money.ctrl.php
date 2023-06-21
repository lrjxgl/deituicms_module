<?php
class gread_shop_moneyControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$shop=M("mod_gread_shop")->selectRow("shopid=".SHOPID);
		$shopmoney=MM("gread","gread_shop_money")->get(SHOPID);
		MM("gread","gread_shop_earnest")->get(SHOPID);
		$list=M("mod_gread_shop_money_log")->select(array(
			"where"=>"shopid=".SHOPID,
			"order"=>"id DESC",
			"limit"=>48
		)); 
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"shopmoney"=>$shopmoney,
			"list"=>$list
		));
		$this->smarty->display("gread_shop_money/index.html");
	}
	
}