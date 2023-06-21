<?php
class cy2cControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}

	public function onDefault(){
		//推荐
		$recList=MM("cy2c","cy2c_product")->Dselect(array(
			"where"=>" status=1 AND isrecommend=1 ",
			"limit"=>24,
			"order"=>"id DESC"
		));
		$hotList=MM("cy2c","cy2c_product")->Dselect(array(
			"where"=>" status=1 AND isrecommend=1 ",
			"limit"=>24,
			"order"=>"id DESC"
		));
		$fromapp=get("fromapp");
		switch($fromapp){
			case "uniapp":
				$flashList=M("ad")->listByNo("uniapp-cy2c-index");
				$adList=M("ad")->listByNo("uniapp-cy2c-ad");
				$navList=M("ad")->listByNo("uniapp-cy2c-nav");
				
				break;
			default:
				$flashList=M("ad")->listByNo("wap-cy2c-index");
				$adList=M("ad")->listByNo("wap-cy2c-ad");
				$navList=M("ad")->listByNo("wap-cy2c-nav");
				break;
		}
		$seo=M("seo")->get("cy2c","default");
		$place=M("mod_cy2c_place")->selectRow("placeid=".PLACEID);
		$this->smarty->goAssign(array(
			"navList"=>$navList,
			"recList"=>$recList,
			"hotList"=>$hotList,
			"flashList"=>$flashList,
			"adList"=>$adList,
			"seo"=>$seo,
			"place"=>$place
		));	
		
		$this->smarty->display("index.html");
	}
	 	
}

?>