<?php
class s2cControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}

	public function onDefault(){
		//推荐
		$recList=MM("s2c","s2c_product")->Dselect(array(
			"where"=>" status=1 AND isrecommend=1 ",
			"limit"=>24,
			"order"=>"id DESC"
		));
		$hotList=MM("s2c","s2c_product")->Dselect(array(
			"where"=>" status=1 AND isrecommend=1 ",
			"limit"=>24,
			"order"=>"id DESC"
		));
		$fromapp=get("fromapp");
		switch($fromapp){
			case "uniapp":
				$flashList=M("ad")->listByNo("uniapp-s2c-index");
				$navList=M("ad")->listByNo("uniapp-s2c-nav");
				$adList=M("ad")->listByNo("uniapp-s2c-ad");
				break;
			default:
				$flashList=M("ad")->listByNo("wap-s2c-index");
				$navList=M("ad")->listByNo("wap-s2c-nav");
				$adList=M("ad")->listByNo("wap-s2c-ad");
				break;
		}
		//社区
		$shequ=[];
		 /*
		$shequ=MM("s2c","s2c_shequ")->selectRow("scid=".SCID);
		if($shequ){
			$team=MM("s2c","s2c_team")->selectRow("teamid=".$shequ["teamid"]);
			$shequ["nickname"]=$team["nickname"];
			$shequ["userhead"]=images_site($team["userhead"]);
		}
		*/ 
	    $team=MM("s2c","s2c_team")->selectRow("teamid=".TEAMID);
		$team["userhead"]=images_site($team["userhead"]);
		$seo=M("seo")->get("s2c","default");
		$site=M("site")->get();
		$catList=MM("s2c","s2c_category")->children(0);
		$sendTime=MM("s2c","s2c_order")->getSendTime();
		$this->smarty->goAssign(array(
			"seo"=>$seo,
			"recList"=>$recList,
			"hotList"=>$hotList,
			"flashList"=>$flashList,
			"shequ"=>$shequ,
			"team"=>$team,
			"adList"=>$adList,
			"site"=>$site,
			"navList"=>$navList,
			"catList"=>$catList,
			 "sendTime"=>$sendTime
		));	
		$this->smarty->display("index.html");
	}
	 	
}

?>