<?php
class pinche_couponControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$data=M("dataapi")->getWord("拼车邀请有奖");
		$this->smarty->goAssign(array(
			"data"=>$data
		));
		$this->smarty->display("pinche_coupon/index.html");
	}	
	
}