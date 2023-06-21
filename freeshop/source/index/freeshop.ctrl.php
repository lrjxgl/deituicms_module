<?php
class freeshopControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onTest(){
		MM("freeshop","freeshop_order")->finish(16);
	}
	public function onDefault(){
		//广告
		$fromapp=get("fromapp");
		switch($fromapp){
			case "uniapp":
				$flashList=M("ad")->listByNo("uniapp-freeshop-index");
				$adList=M("ad")->listByNo("uniapp-freeshop-ad");
				$navList=M("ad")->listByNo("uniapp-freeshop-nav"); 
				break;
			default:
				$flashList=M("ad")->listByNo("wap-freeshop-index");
				$adList=M("ad")->listByNo("wap-freeshop-ad");
				$navList=M("ad")->listByNo("wap-freeshop-nav"); 
				break;
		}
		$seo=M("seo")->get("freeshop","index");
		if(empty($seo)){
			$seo=array(
				"title"=>"闲时优惠-在空闲的时候让利接单"
			);
		}
		
		$this->smarty->goAssign(array(
			"flashList"=>$flashList,
			"adList"=>$adList,
			"navList"=>$navList,
			"seo"=>$seo
		));
		$this->smarty->display("freeshop/index.html");
	}
	
	public function onUser(){
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
	
	public function onClear(){
		MM("freeshop","freeshop_product")->update(array(
			"status"=>2
		)," etime<".time()." ");
		echo "处理成功";
	}
	
}
