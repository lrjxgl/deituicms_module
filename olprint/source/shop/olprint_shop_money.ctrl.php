<?php
class olprint_shop_moneyControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$shop=M("mod_olprint_shop")->selectRow("shopid=".SHOPID);
		$shopmoney=MM("olprint","olprint_shop_money")->get(SHOPID);
		$list=M("mod_olprint_shop_money_log")->select(array(
			"where"=>"shopid=".SHOPID,
			"order"=>"id DESC",
			"limit"=>48
		)); 
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"shopmoney"=>$shopmoney,
			"list"=>$list
		));
		$this->smarty->display("olprint_shop_money/index.html");
	}
	
}