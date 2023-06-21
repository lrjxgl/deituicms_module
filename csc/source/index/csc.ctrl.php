<?php
class cscControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}

	public function onDefault(){
		 
		$shop=M("mod_csc_shop")->selectRow(array(
			"where"=>"shopid=".SHOPID,
			"fields"=>"shopid,shopname,yystatus"
		));
		if($shop["yystatus"]==1){
			$shop["yystatus_name"]="营业中";
		}elseif($shop["yystatus"]==0){
			$shop["yystatus_name"]="装修中";
		}else{
			$shop["yystatus_name"]="暂停营业";
		}
		 
		$recList=MM("csc","csc_product")->Dselect(array(
			"where"=>" shopid=".SHOPID." AND isrecommend=1 AND status=1 ",
			"limit"=>12,
			"order"=>" id DESC"
		));
		$hotList=MM("csc","csc_product")->Dselect(array(
			"where"=>" shopid=".SHOPID." AND ishot=1 AND status=1 ",
			"limit"=>12,
			"order"=>" id DESC"
		));
		$fromapp=get("fromapp");
		switch($fromapp){
			case "uniapp":
				$flashList=M("ad")->listByNo("uniapp-csc-index");
				$adList=M("ad")->listByNo("uniapp-csc-ad");
				$navList=M("ad")->listByNo("uniapp-csc-nav");
				break;
			default:
				$flashList=M("ad")->listByNo("wap-csc-index");
				$adList=M("ad")->listByNo("wap-csc-ad");
				$navList=M("ad")->listByNo("wap-csc-nav");
				break;
		}
		$site=M("site")->get();
		$seo=M("seo")->get("csc","default"); 
		$this->smarty->assign(array(
			"seo"=>$seo,
			"site"=>$site
		)); 
		 
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"recList"=>$recList,
			"hotList"=>$hotList,
			"navList"=>$navList,
			"flashList"=>$flashList,
			"adList"=>$adList,
			
		));	
		$this->smarty->display("index.html");
	}
	 	
}

?>