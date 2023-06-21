<?php
class olprint_shop_earnestControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$data=MM("olprint","olprint_shop_earnest")->get(SHOPID);
		
		$moneyList=array(			 
			1000,		 
			5000,
			10000
		);
		if($data){
			foreach($moneyList as $k=>$v){
				if($v<=$data['money']){
					unset($moneyList[$k]);
				}
			}
		}
		
		$this->smarty->goAssign(array(
			"data"=>$data,
			"moneyList"=>$moneyList
		));
		$this->smarty->display("olprint_shop_earnest/index.html");
	}
}
