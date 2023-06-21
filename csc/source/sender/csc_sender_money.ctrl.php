<?php
class csc_sender_moneyControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$sender=M("mod_csc_sender")->selectRow("senderid=".SENDERID);
		$sendermoney=M("mod_csc_sender_money")->selectRow("senderid=".SENDERID);
		$list=M("mod_csc_sender_money_log")->select(array(
			"where"=>"senderid=".SENDERID,
			"order"=>"id DESC",
			"limit"=>48
		)); 
		$this->smarty->goAssign(array(
			"sender"=>$sender,
			"sendermoney"=>$sendermoney,
			"list"=>$list
		));
		$this->smarty->display("csc_sender_money/index.html");
	}
	
}