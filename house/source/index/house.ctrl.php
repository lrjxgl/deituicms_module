<?php
class houseControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$loupanList=MM("house","house_loupan")->Dselect(array(
			"where"=>" isrecommend=1 AND status=1 ",
			"limit"=>4
		));
		$recList=MM("house","house_tags")->gethouseByKey("recommend");
		$newList=MM("house","house_tags")->gethouseByKey("new");
		//广告轮显
		$fromapp=get("fromapp");
		switch($fromapp){
			case "uniapp":
				$flashList=M("ad")->listByNo("uniapp-house-index");
				$adList=M("ad")->listByNo("uniapp-house-ad");
				$navList=M("ad")->listByNo("uniapp-house-nav");
				break;
			default:
				$flashList=M("ad")->listByNo("wap-house-index");
				$adList=M("ad")->listByNo("wap-house-ad");
				$navList=M("ad")->listByNo("wap-house-nav");
				break;
		}
		$seo=M("seo")->get("house","default");
		 
		$this->smarty->goassign(
			array(
				"seo"=>$seo,
				"newList"=>$newList,
				"recList"=>$recList,
				"url"=>$url,
				"navList"=>$navList,
				"flashList"=>$flashList,
				"adList"=>$adList,
				"loupanList"=>$loupanList
			)
		);
		$this->smarty->display("index.html");
	}
	
}
?>