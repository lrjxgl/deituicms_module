<?php
class householdControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}

	public function onDefault(){
		//推荐
		$recList=MM("household","household_product")->Dselect(array(
			"where"=>" status=1 AND isrecommend=1 ",
			"limit"=>12,
			"order"=>"id DESC"
		));
		$hotList=MM("household","household_product")->Dselect(array(
			"where"=>" status=1 AND isrecommend=1 ",
			"limit"=>12,
			"order"=>"id DESC"
		));
		$fromapp=get("fromapp");
		switch($fromapp){
			case "uniapp":
				$flashList=M("ad")->listByNo("uniapp-household-index");
				$adList=M("ad")->listByNo("uniapp-household-ad");
				$navList=M("ad")->listByNo("uniapp-household-nav");
				
				break;
			default:
				$flashList=M("ad")->listByNo("wap-household-index");
				$adList=M("ad")->listByNo("wap-household-ad");
				$navList=M("ad")->listByNo("wap-household-nav");
				break;
		}
		$seo=M("seo")->get("household","default");
		$site=M("site")->get();
		$bmList=MM("household","household_group")->getProductByKey('bimai');
		$this->smarty->assign(array(
			"seo"=>$seo
		));
		//cityid
		$cityid=M("city")->getCityid();
		$city=M("city")->selectRow("id=".$cityid);
		 
		$this->smarty->goAssign(array(
			"navList"=>$navList,
			"recList"=>$recList,
			"hotList"=>$hotList,
			"flashList"=>$flashList,
			"adList"=>$adList,
			"city"=>$city,
			"site"=>$site,
			"bmList"=>$bmList
		));
	 
		$tpl=M("pagetpl")->get("household","index");	
		$this->smarty->display($tpl);
	}
	 	
}

?>