<?php
class taoke_categoryControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$catList=MM("taoke","taoke_category")->children(0);
		$this->smarty->goAssign(array(
			"catList"=>$catList
		));
		$this->smarty->display("taoke_category/index.html");
	}
	
}