<?php
class freeshop_shop_moneyControl extends skymvc{
	public $shopid;
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		$userid=M("login")->userid;
		$shop=M("mod_freeshop_shop")->selectRow("userid=".$userid);
		if(empty($shop)){
			$this->goAll("暂无权限",1);
		}
		$this->shopid=$shop["shopid"];
		 
	}
	public function onDefault(){
		$shop=M("mod_freeshop_shop")->selectRow("shopid=".$this->shopid);
		$shopmoney=MM("freeshop","freeshop_shop_money")->get($this->shopid);
		$list=M("mod_freeshop_shop_money_log")->select(array(
			"where"=>"shopid=".$this->shopid,
			"order"=>"id DESC",
			"limit"=>48
		)); 
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"shopmoney"=>$shopmoney,
			"list"=>$list
		));
		$this->smarty->display("freeshop_shop_money/index.html");
	}
	
}