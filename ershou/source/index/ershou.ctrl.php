<?php
class ershouControl extends skymvc{
	public function onDefault(){
		//广告
		$fromapp=get("fromapp");
		switch($fromapp){
			case "uniapp":
				$flashList=M("ad")->listByNo("uniapp-ershou-index");
				$adList=M("ad")->listByNo("uniapp-ershou-ad");
				$navList=M("ad")->listByNo("uniapp-ershou-nav"); 
				$adRecycle=M("ad")->listByNo("uniapp-ershou-recycle"); 
				break;
			default:
				$flashList=M("ad")->listByNo("wap-ershou-index");
				$adList=M("ad")->listByNo("wap-ershou-ad");
				$navList=M("ad")->listByNo("wap-ershou-nav"); 
				//左四个 咸鱼有品
				$ypList=M("ad")->listByNo("wap-ershou-youpin");
				$adRecycle=M("ad")->listByNo("wap-ershou-recycle");
				break;
		}
		$site=M("site")->get();
		$seo=M("seo")->get("ershou","index");
		//金币兑换
		$goldGoods=MM("gold","gold_product")->Dselect(array(
			"where"=>" status=1 ",
			"limit"=>2
		));
		$this->smarty->goAssign(array(
			"flashList"=>$flashList,
			"adList"=>$adList,
			"navList"=>$navList,
			"seo"=>$seo,
			"goldGoods"=>$goldGoods,
			"ypList"=>$ypList,
			"site"=>$site,
			"adRecycle"=>$adRecycle
		));
		$this->smarty->display("ershou/index.html");
	}
	
	public function onTest(){
		$arr=M("map")->parseAddr("福鼎前岐鹿兴12号");
		 
		print_r($arr);
	}
	
}