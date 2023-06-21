<?php
class b2bControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}

	public function onDefault(){
		//推荐
		$recList=MM("b2b","b2b_group")->getProductByKey("recommend");
		$hotList=MM("b2b","b2b_group")->getProductByKey("hot");
		$fromapp=get("fromapp");
		switch($fromapp){
			case "uniapp":
				$flashList=M("ad")->listByNo("uniapp-b2b-index");
				$adList=M("ad")->listByNo("uniapp-b2b-ad");
				$navList=M("ad")->listByNo("uniapp-b2b-nav");
				break;
			default:
				$flashList=M("ad")->listByNo("wap-b2b-index");
				$adList=M("ad")->listByNo("wap-b2b-ad");
				$navList=M("ad")->listByNo("wap-b2b-nav");
				break;
		}
		$seo=M("seo")->get("b2b","default");
		$catList=MM("b2b","b2b_shop_category")->children(0);
		$recShop=MM("b2b","b2b_shop")->DselectWindow(array(
			"where"=>" status=1 AND isrecommend=1 AND yystatus=1 ",
			"limit"=>4
		));
		$vipShop=MM("b2b","b2b_shop")->vipList(4);
		$this->smarty->assign(array(
			"seo"=>$seo
		)); 
		 
		$this->smarty->goAssign(array(
			"recShop"=>$recShop,
			"navList"=>$navList,
			"recList"=>$recList,
			"hotList"=>$hotList,
			"flashList"=>$flashList,
			"adList"=>$adList,
			"catList"=>$catList,
			"vipShop"=>$vipShop 
			
		));	
		$this->smarty->display("index.html");
	}
	 	
}

?>