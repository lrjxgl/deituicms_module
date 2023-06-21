<?php
class f2cControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}

	public function onDefault(){
		//推荐
		$bmList=MM("f2c","f2c_group")->getProductByKey('bimai');
		 
		$recList=MM("f2c","f2c_product")->Dselect(array(
			"where"=>" status=1 AND isrecommend=1 ",
			"limit"=>24,
			"order"=>"id DESC"
		));
		$hotList=MM("f2c","f2c_product")->Dselect(array(
			"where"=>" status=1 AND isrecommend=1 ",
			"limit"=>24,
			"order"=>"id DESC"
		));
		$fromapp=get("fromapp");
		switch($fromapp){
			case "uniapp":
				$flashList=M("ad")->listByNo("uniapp-f2c-index");
				$navList=M("ad")->listByNo("uniapp-f2c-nav");
				break;
			default:
				$flashList=M("ad")->listByNo("wap-f2c-index");
				$navList=M("ad")->listByNo("wap-f2c-nav");
				break;
		}
		$team=M("mod_f2c_team")->selectRow("teamid=".TEAMID);
		if($team){
			$team["userhead"]=images_site($team["userhead"]); 
		}
		$config=M("mod_f2c_config")->selectRow("1");
		$seo=M("seo")->get("f2c","default");
		$site=M("site")->get();
		$this->smarty->goAssign(array(
			"recList"=>$recList,
			"seo"=>$seo,
			"hotList"=>$hotList,
			"flashList"=>$flashList,
			"navList"=>$navList,
			"team"=>$team,
			"site"=>$site,
			"bmList"=>$bmList
		));	
		if($team && $config["shoptype"]==0){
			$this->smarty->display("f2c_team_shop/index.html");
		}else{
			$this->smarty->display("index.html");
		}
		
	}
	 	
}

?>