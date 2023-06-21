<?php
class household_senderControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		 
		$sender=MM("household","household_sender")->get(SENDERID);
		 
		$this->smarty->goAssign(array(
			"sender"=>$sender
		));
		if($sender["isauth"]){
			$this->smarty->display("household_sender/index.html");
		}else{
			$this->smarty->display("household_sender/auth.html");
		}
		
	}
	 
	public function onAdd(){
		$sender=MM("household","household_sender")->get(SENDERID);
		 
		$this->smarty->goAssign(array(
			"sender"=>$sender
		));
		$this->smarty->display("household_sender/add.html");
	}
	
	public function onSave(){
		$sender=MM("household","household_sender")->get(SENDERID);
		$nickname=post("nickname","h");
		$description=post("description","h");
		$address=post("address","h");
		MM("household","household_sender")->update(array(
			"nickname"=>$nickname,
			"description"=>$description,
			"address"=>$address
			 
		),"senderid=".SENDERID);
		$this->goAll("保存成功");
	}
	
	 
	
	
}
?>