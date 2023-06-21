<?php
class ershou_categoryControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$catList=MM("ershou","ershou_category")->children(0);
		 
		$this->smarty->goAssign(array(
			"catList"=>$catList
		));
		$this->smarty->display("ershou_category/index.html");
	}
	
	public function onList(){
		$pid=get("pid","i");
		$catList=MM("ershou","ershou_category")->select(array(
			"where"=>" pid=".$pid." AND status=1 ",
			"order"=>" orderindex ASC"
		));
		$this->goAll("success",0,array(
			"list"=>$catList
		));
	}
	
}