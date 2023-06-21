<?php
class csc_searchControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$keyword=get("keyword","h");
		$hotSearch=array(
			"闽侯甜橙",
			"卤味",
			"酸菜鱼",
			"牛排",
			"杨梅酒",
			"大闸蟹",
			"母婴",
			"苹果",
			"花菜"
		);
		$this->smarty->goAssign(array(
			"keyword"=>$keyword,
			"hotSearch"=>$hotSearch
		));
		$this->smarty->display("csc_search/index.html");
	}
	public function onProduct(){
		$where=" status=1 AND shopid=".SHOPID;
		$keyword=get("keyword","h");
		$where.=" AND title like '%".$keyword."%' ";
		$ops=array(
			"where"=>$where,
			"limit"=>48
		);
		$list=MM("csc","csc_product")->Dselect($ops);
		$this->smarty->goAssign(array(
			"list"=>$list
		));
	}
	public function onShop(){
		$where=" status=1 ";
		$keyword=get("keyword","h");
		$where.=" AND shopname like '%".$keyword."%' ";
		$ops=array(
			"where"=>$where,
			"limit"=>48
		);
		$list=MM("csc","csc_shop")->DselectWindow($ops);
		$this->smarty->goAssign(array(
			"list"=>$list
		));
	}
	
}