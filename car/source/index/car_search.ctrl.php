<?php
class car_searchControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$keyword=get("keyword","h");
		$this->smarty->goAssign(array(
			"keyword"=>$keyword
		));
		$this->smarty->display("car_search/index.html");
	}
	public function onProduct(){
		$where=" status=1 ";
		$keyword=get("keyword","h");
		$where.=" AND title like '%".$keyword."%' ";
		$ops=array(
			"where"=>$where,
			"limit"=>48
		);
		$list=MM("car","car_product")->Dselect($ops);
		$this->smarty->goAssign(array(
			"list"=>$list
		));
	}
	 
	
}