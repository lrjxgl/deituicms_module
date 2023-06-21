<?php
class wmo2o_categoryControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$catList=MM("wmo2o","wmo2o_category")->children(0);
		$this->smarty->goAssign(array(
			"catList"=>$catList
		));
		$this->smarty->display("wmo2o_category/index.html");
	}
	
}