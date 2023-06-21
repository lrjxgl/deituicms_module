<?php
class flkControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}

	public function onDefault(){
		//推荐
		$recList=MM("flk","flk_group")->getProductByKey("recommend");
		$hotList=MM("flk","flk_group")->getProductByKey("hot");
		$fromapp=get("fromapp");
		switch($fromapp){
			case "uniapp":
				$flashList=M("ad")->listByNo("uniapp-flk-index");
				$adList=M("ad")->listByNo("uniapp-flk-ad");
				$navList=M("ad")->listByNo("uniapp-flk-nav");
				break;
			default:
				$flashList=M("ad")->listByNo("wap-flk-index");
				$adList=M("ad")->listByNo("wap-flk-ad");
				$navList=M("ad")->listByNo("wap-flk-nav");
				break;
		}
		$seo=M("seo")->get("flk","default");
		$catList=MM("flk","flk_shop_category")->children(0);
		$recShop=MM("flk","flk_shop")->DselectWindow(array(
			"where"=>" status=1 AND isrecommend=1 ",
			"limit"=>4
		));
		//收藏的商城
		$userid=M("login")->userid;
		$favShopList=MM("flk","flk_shop")->favList($userid,"shopid,imgurl,shopname");
		 
		$this->smarty->assign(array(
			"seo"=>$seo
		)); 
		$flks=MM("flk","flk_queue")->statAll(); 
		$this->smarty->goAssign(array(
			"favShopList"=>$favShopList,
			"recShop"=>$recShop,
			"navList"=>$navList,
			"recList"=>$recList,
			"hotList"=>$hotList,
			"flashList"=>$flashList,
			"adList"=>$adList,
			"catList"=>$catList,
			"flks"=>$flks["flks"],
			 "flk_num"=>$flks["flk_num"]
			
		));	
		$this->smarty->display("index.html");
	}
	
	public function onRule(){
		$ops=array(
			"一折卡劵可以返还所有消费额度，卡券费用为消费额的1折。",
			"一折卡劵是一种可以通过商家排队返利，或者邀请朋友消费返利的卡券。",
			"某些商家如果卡券排队人数多，则会导致返还时间长，请考虑购买。另外我们推荐一些新商家给正在排队的客户。",
			"有可能存在无法返利情况，如商家退出平台，则由平台返还卡券费用，请合理消费。"
		);
		echo json_encode($ops);
	}	
}

?>