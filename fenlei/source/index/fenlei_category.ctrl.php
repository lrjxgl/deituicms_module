<?php
class fenlei_categoryControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$catList=MM("fenlei","fenlei_category")->children(0,1);
		 
		$this->smarty->goAssign(array(
			"catList"=>$catList
		));
		$this->smarty->display("fenlei_category/index.html");
	}
	public function onAdd(){
		$catList=MM("fenlei","fenlei_category")->children(0,1);
		 
		$this->smarty->goAssign(array(
			"catList"=>$catList
		));
		$this->smarty->display("fenlei_category/add.html");
	}
	
}