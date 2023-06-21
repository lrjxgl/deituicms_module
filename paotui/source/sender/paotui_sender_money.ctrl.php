<?php
class paotui_sender_moneyControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$sender=MM("paotui","paotui_sender")->get(SENDERID);
		 
		$list=M("mod_paotui_sender_moneylog")->select(array(
			"where"=>"senderid=".SENDERID,
			"order"=>"id DESC",
			"limit"=>48
		)); 
		$this->smarty->goAssign(array(
			"sender"=>$sender,
			 
			"list"=>$list
		));
		$this->smarty->display("paotui_sender_money/index.html");
	}
	
}