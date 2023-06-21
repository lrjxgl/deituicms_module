<?php
class wmo2o_searchControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$keyword=get("keyword","h");
		$this->smarty->goAssign(array(
			"keyword"=>$keyword
		));
		$this->smarty->display("wmo2o_search/index.html");
	}
	public function onProduct(){
		$where=" status=1 ";
		$keyword=get("keyword","h");
		$where.=" AND title like '%".$keyword."%' ";
		$start=get("per_page","i");
		$limit=12;
		$ops=array(
			"where"=>$where,
			"limit"=>$limit,
			"start"=>$start
		);
		$rscount=true;
		$list=MM("wmo2o","wmo2o_product")->Dselect($ops,$rscount);
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page
		));
	}
	public function onShop(){
		$where=" status=1 ";
		$keyword=get("keyword","h");
		$where.=" AND shopname like '%".$keyword."%' ";
		$start=get("per_page","i");
		$limit=12;
		$ops=array(
			"where"=>$where,
			"limit"=>$limit,
			"start"=>$start
		);
		$rscount=true;
		$list=MM("wmo2o","wmo2o_shop")->DselectWindow($ops,$rscount);
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page
		));
	}
	
}