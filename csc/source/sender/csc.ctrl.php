<?php
class cscControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		 
		$sender=MM("csc","csc_sender")->get(SENDERID);
		$smoney=MM("csc","csc_sender_money")->get(SENDERID); 
		$neworder=MM("csc","csc_sender_order")->selectOne(array(
			"where"=>" senderid=".SENDERID." AND status=1 ",
			"fields"=>"count(*) as ct"
		));
		$finishOrder=MM("csc","csc_sender_order")->selectOne(array(
			"where"=>" senderid=".SENDERID." AND status=3 ",
			"fields"=>"count(*) as ct"
		));
		$finishMoney=MM("csc","csc_sender_order")->selectOne(array(
			"where"=>" senderid=".SENDERID." AND status=3 ",
			"fields"=>"sum(money) as ct"
		));
		$this->smarty->goAssign(array(
			"sender"=>$sender,
			"neworder"=>$neworder,
			"finishOrder"=>$finishOrder,
			"finishMoney"=>$finishMoney,
			"smoney"=>$smoney
		));
		$this->smarty->display("csc/index.html");
	}
	
}
?>