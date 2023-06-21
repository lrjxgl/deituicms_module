<?php
class fishing_free_accountControl extends skymvc{
	
	public function onDefault(){
		
	}
	public function onGet(){
		$placeid=get("placeid","i");
		$account=MM("fishing","fishing_free_account")->get($placeid);
		$logList=M("mod_fishing_free_account_log")->select(array(
			"where"=>"placeid=".$placeid,
			"order"=>"id DESC",
			"limit"=>24
		));
		$this->smarty->goAssign(array(
			"account"=>$account,
			"logList"=>$logList
		));
	}
	
}