<?php
class b2b_categoryControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$catList=MM("b2b","b2b_category")->children(0);
		$this->smarty->goAssign(array(
			"catList"=>$catList
		));
		$this->smarty->display("b2b_category/index.html");
	}
	
}