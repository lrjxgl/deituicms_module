<?php
class gxnyControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}

	public function onDefault(){
		$shopid=MM("gxny","gxny_shop")->inShopid();
		//广告
		$fromapp=get("fromapp");
		switch($fromapp){
			case "uniapp":
				$flashList=M("ad")->listByNo("uniapp-gxny-index");
				$adList=M("ad")->listByNo("uniapp-gxny-ad");
				$navList=M("ad")->listByNo("uniapp-gxny-nav"); 
				break;
			default:
				$flashList=M("ad")->listByNo("wap-gxny-index");
				$adList=M("ad")->listByNo("wap-gxny-ad");
				$navList=M("ad")->listByNo("wap-gxny-nav"); 
				break;
		}
		$seo=M("seo")->get("gxny","default"); 
		$shop=MM("gxny","gxny_shop")->selectRow("shopid=".$shopid);
		$this->smarty->goAssign(array(
			"shopid"=>$shopid,
			"flashList"=>$flashList,
			"adList"=>$adList,
			"navList"=>$navList,			 
			"seo"=>$seo,
			"shop"=>$shop
		)); 
		$this->smarty->display("gxny/index.html");
	}
}

?>