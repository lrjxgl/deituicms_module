<?php
class jdo2oControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}

	public function onDefault(){
		 
		$shop=M("mod_jdo2o_shop")->selectRow(array(
			"where"=>"shopid=".SHOPID,
			"fields"=>"shopid,shopname,yystatus,open_mall"
		));
		if($shop["yystatus"]==1){
			$shop["yystatus_name"]="营业中";
		}elseif($shop["yystatus"]==0){
			$shop["yystatus_name"]="装修中";
		}else{
			$shop["yystatus_name"]="暂停营业";
		}
		 
		$recList=MM("jdo2o","jdo2o_product")->Dselect(array(
			"where"=>" shopid=".SHOPID." AND isrecommend=1 AND status=1 ",
			"limit"=>12,
			"order"=>" id DESC"
		));
		$hotList=MM("jdo2o","jdo2o_product")->Dselect(array(
			"where"=>" shopid=".SHOPID." AND ishot=1 AND status=1 ",
			"limit"=>12,
			"order"=>" id DESC"
		));
		$fromapp=get("fromapp");
		switch($fromapp){
			case "uniapp":
				$flashList=M("ad")->listByNo("uniapp-jdo2o-index");
				$adList=M("ad")->listByNo("uniapp-jdo2o-ad");
				$navList=M("ad")->listByNo("uniapp-jdo2o-nav");
				break;
			default:
				$flashList=M("ad")->listByNo("wap-jdo2o-index");
				$adList=M("ad")->listByNo("wap-jdo2o-ad");
				$navList=M("ad")->listByNo("wap-jdo2o-nav");
				break;
		}
		$site=M("site")->get();
		$seo=M("seo")->get("jdo2o","default"); 
		$this->smarty->assign(array(
			
			"site"=>$site
		)); 
		 
		$this->smarty->goAssign(array(
			"seo"=>$seo,
			"shop"=>$shop,
			"recList"=>$recList,
			"hotList"=>$hotList,
			"navList"=>$navList,
			"flashList"=>$flashList,
			"adList"=>$adList,
			
		));	
		$this->smarty->display("jdo2o/index.html");
	}
	
	public function onList1(){
		
	}
	 	
}

?>