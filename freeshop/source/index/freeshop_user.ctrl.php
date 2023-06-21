<?php
class freeshop_userControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$user=M("user")->selectRow(array("where"=>" userid=".$userid));
		$user['user_head']=images_site($user['user_head']);
		$shop=MM("freeshop","freeshop_shop")->selectRow("userid=".$userid);
		$shopmoney=false;
		if($shop){
			$shopmoney=MM("freeshop","freeshop_shop_money")->get($shop["shopid"]);
		} 
		$this->smarty->goAssign(array(
			"data"=>$user,
			"shop"=>$shop,
			"shopmoney"=>$shopmoney
			 
		));
		if($shop){
			$this->smarty->display("freeshop/shop.html");
		}else{
			$this->smarty->display("freeshop/user.html");
		}
	}
	
}