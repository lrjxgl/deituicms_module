<?php
class mmjzControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}

	public function onDefault(){
		//推荐
		$shop=M("mod_mmjz_shop")->selectRow(array(
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
		 
		$recList=MM("mmjz","mmjz_product")->Dselect(array(
			"where"=>" shopid=".SHOPID." AND isrecommend=1 AND status=1 ",
			"limit"=>12,
			"order"=>" id DESC"
		));
		$hotList=MM("mmjz","mmjz_product")->Dselect(array(
			"where"=>" shopid=".SHOPID." AND ishot=1 AND status=1 ",
			"limit"=>12,
			"order"=>" id DESC"
		));
		$fromapp=get("fromapp");
		switch($fromapp){
			case "uniapp":
				$flashList=M("ad")->listByNo("uniapp-mmjz-index");
				$adList=M("ad")->listByNo("uniapp-mmjz-ad");
				$navList=M("ad")->listByNo("uniapp-mmjz-nav");
				break;
			default:
				$flashList=M("ad")->listByNo("wap-mmjz-index");
				$adList=M("ad")->listByNo("wap-mmjz-ad");
				$navList=M("ad")->listByNo("wap-mmjz-nav");
				break;
		}
		$seo=M("seo")->get("mmjz","default");
		$catList=MM("mmjz","mmjz_shop_category")->children(0);
		$recShop=MM("mmjz","mmjz_shop")->DselectWindow(array(
			"where"=>" status=1 AND isrecommend=1 AND yystatus=1 ",
			"limit"=>4
		));
		$vipShop=MM("mmjz","mmjz_shop")->vipList(4);
		$this->smarty->assign(array(
			"seo"=>$seo
		)); 
		 
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"recList"=>$recList,
			"hotList"=>$hotList,
			"navList"=>$navList,
			"flashList"=>$flashList,
			"adList"=>$adList,
			
		));	
		$this->smarty->display("mmjz/index.html");
	}
	 	
}

?>