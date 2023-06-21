<?php
class exueControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$fromapp=get("fromapp");
		switch($fromapp){
			case "uniapp":
				$flashList=M("ad")->listByNo("uniapp-exue-index");
				$adList=M("ad")->listByNo("uniapp-exue-ad");
				$navList=M("ad")->listByNo("uniapp-exue-nav");
				break;
			default:
				$flashList=M("ad")->listByNo("wap-exue-index");
				$adList=M("ad")->listByNo("wap-exue-ad");
				$navList=M("ad")->listByNo("wap-exue-nav");
				break;
		}
		$sxList=MM("exue","exue_course")->Dselect(array(
			"where"=>" status=1 AND stype=1 "
		));
		$recList=MM("exue","exue_course")->Dselect(array(
			"where"=>" status=1  AND site_index=1 "
		));
		$seo=M("seo")->get("exue");
		$this->smarty->goAssign(array(
			"recShop"=>$recShop,
			"navList"=>$navList,
			"sxList"=>$sxList,
			"recList"=>$recList,
			"hotList"=>$hotList,
			"flashList"=>$flashList,
			"adList"=>$adList,
			"catList"=>$catList,
			"seo"=>$seo 
			
		));	
		$this->smarty->display("exue/index.html");
	}
	
	public function onUser(){
		M("login")->checkLogin();
		$user=M("login")->getUser();
		$this->smarty->goAssign(array(
			"user"=>$user
		));
		$this->smarty->display("exue/user.html");
	}
}
?>