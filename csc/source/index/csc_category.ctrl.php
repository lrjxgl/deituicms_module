<?php
class csc_categoryControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
		$catList=MM("csc","csc_category")->children(0);
		$this->smarty->goAssign(array(
			"catList"=>$catList
		));
		$this->smarty->display("csc_category/index.html");
	}
	public function onTpl(){
		$this->smarty->display("csc_category/tpl.html");
	}
}