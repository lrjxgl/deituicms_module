<?php
class pddControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}

	public function onDefault(){
		//推荐
		$recList=MM("pdd","pdd_product")->Dselect(array(
			"where"=>" status=1 AND isrecommend=1 ",
			"limit"=>24,
			"order"=>"id DESC"
		));
		if($recList){
			foreach($recList as $r){
				$shopids[]=$r["shopid"];
			}
			$sps=MM("pdd","pdd_shop")->getListByIds($shopids);
			foreach($recList as $k=>$v){
				$v["shop"]=$sps[$v["shopid"]];
				$recList[$k]=$v;
			}
		}
	 
		$hotList=MM("pdd","pdd_product")->Dselect(array(
			"where"=>" status=1 AND isrecommend=1 ",
			"limit"=>24,
			"order"=>"id DESC"
		));
		$fromapp=get("fromapp");
		switch($fromapp){
			case "uniapp":
				$flashList=M("ad")->listByNo("uniapp-pdd-index");
				$adList=M("ad")->listByNo("uniapp-pdd-ad");
				$navList=M("ad")->listByNo("uniapp-pdd-nav");
				break;
			default:
				$flashList=M("ad")->listByNo("wap-pdd-index");
				$adList=M("ad")->listByNo("wap-pdd-ad");
				$navList=M("ad")->listByNo("wap-pdd-nav");
				break;
		}
		$seo=M("seo")->get("pdd","default");
		$catList=MM("pdd","pdd_shop_category")->children(0);
		$recShop=MM("pdd","pdd_shop")->DselectWindow(array(
			"where"=>" status=1 AND isrecommend=1 ",
			"limit"=>30
		));
		$this->smarty->assign(array(
			"seo"=>$seo
		)); 
		$catList=M("mod_pdd_category")->select(array(
			"where"=>" status=1 AND pid=0 ",
			"order"=>"orderindex ASC"
		)); 
		$this->smarty->goAssign(array(
			"recShop"=>$recShop,
			"navList"=>$navList,
			"recList"=>$recList,
			"hotList"=>$hotList,
			"catList"=>$catList,
			"flashList"=>$flashList,
			"adList"=>$adList,
			"catList"=>$catList,
			 
			
		));	
		$this->smarty->display("index.html");
	}
	 	
}

?>