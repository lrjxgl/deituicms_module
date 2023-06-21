<?php
class wmo2oControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onTest(){
		$res=MM("wmo2o","wmo2o_shop_notice")->sendNewOrder(2);
		var_dump($res);
	}
	public function onDefault(){
		//推荐
		$recList=MM("wmo2o","wmo2o_group")->getProductByKey("recommend");
		$hotList=MM("wmo2o","wmo2o_group")->getProductByKey("hot");
		$fromapp=get("fromapp");
		switch($fromapp){
			case "uniapp":
				$flashList=M("ad")->listByNo("uniapp-wmo2o-index");
				$adList=M("ad")->listByNo("uniapp-wmo2o-ad");
				$navList=M("ad")->listByNo("uniapp-wmo2o-nav");
				break;
			default:
				$flashList=M("ad")->listByNo("wap-wmo2o-index");
				$adList=M("ad")->listByNo("wap-wmo2o-ad");
				$navList=M("ad")->listByNo("wap-wmo2o-nav");
				break;
		}
		$seo=M("seo")->get("wmo2o","default");
		$catList=MM("wmo2o","wmo2o_shop_category")->children(0);
		$recShop=MM("wmo2o","wmo2o_shop")->DselectWindow(array(
			"where"=>" status=1 AND isrecommend=1 ",
			"limit"=>4
		));
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
			 
			
		));	
		$this->smarty->display("index.html");
	}
	 	
}

?>