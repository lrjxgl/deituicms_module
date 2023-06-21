<?php
class household_sender_moneyControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$sender=MM("household","household_sender")->get(SENDERID);
		 
		$list=M("mod_household_sender_moneylog")->select(array(
			"where"=>"senderid=".SENDERID,
			"order"=>"id DESC",
			"limit"=>48
		)); 
		$this->smarty->goAssign(array(
			"sender"=>$sender,
			 
			"list"=>$list
		));
		$this->smarty->display("household_sender_money/index.html");
	}
	
}