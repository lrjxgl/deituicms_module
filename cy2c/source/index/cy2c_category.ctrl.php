<?php
class cy2c_categoryControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$catList=MM("cy2c","cy2c_category")->children(0);
		$this->smarty->goAssign(array(
			"catList"=>$catList
		));
		$this->smarty->display("cy2c_category/index.html");
	}
	
}