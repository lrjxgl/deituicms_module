<?php
class carControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$fromapp=get("fromapp");
		switch($fromapp){
			case "uniapp":
				$flashList=M("ad")->listByNo("uniapp-car-index");
				$adList=M("ad")->listByNo("uniapp-car-ad");
				$navList=M("ad")->listByNo("uniapp-car-nav");
				
				break;
			default:
				$flashList=M("ad")->listByNo("wap-car-index");
				$adList=M("ad")->listByNo("wap-car-ad");
				$navList=M("ad")->listByNo("wap-car-nav");
				break;
		}
		$seo=M("seo")->get("car","default");
		$site=M("site")->get();
		 
		$this->smarty->assign(array(
			"seo"=>$seo
		));
		//推荐
		$articleList=M("article_tags")->getArticleByKey("car");
		$recList=MM("car","car_product")->Dselect(array(
			"where"=>" status=1 AND isrecommend=1 ",
			"limit"=>12,
			"order"=>"productid DESC"
		));
		$hotList=MM("car","car_product")->Dselect(array(
			"where"=>" status=1 AND isrecommend=1 ",
			"limit"=>12,
			"order"=>"productid DESC"
		));
		 
		$this->smarty->goAssign(array(
			"navList"=>$navList,
			"recList"=>$recList,
			"hotList"=>$hotList,
			"flashList"=>$flashList,
			"adList"=>$adList,		
			"site"=>$site,
			"articleList"=>$articleList
			 
		));
			 
		$tpl=M("pagetpl")->get("car","index");	
		$this->smarty->display($tpl);
		 
	}
	
	public function onUser(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$user=M("user")->selectRow(array("where"=>" userid=".$userid));
		$user['user_head']=images_site($user['user_head']);
		$shop=MM("car","car_shop")->selectRow("userid=".$userid);
		 
		$this->smarty->goAssign(array(
			"data"=>$user,
			"shop"=>$shop 
		));
		if($shop){
			$this->smarty->display("car/shop.html");
		}else{
			$this->smarty->display("car/user.html");
		}
		
	}
	
}
?>