<?php
class paotui_senderControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		 
		$sender=MM("paotui","paotui_sender")->get(SENDERID);
		 
		$this->smarty->goAssign(array(
			"sender"=>$sender
		));
		if($sender["isauth"]){
			$this->smarty->display("paotui_sender/index.html");
		}else{
			$this->smarty->display("paotui_sender/auth.html");
		}
		
	}
	public function onAuth(){
		$sender=MM("paotui","paotui_sender")->get(SENDERID);
		 
		$this->smarty->goAssign(array(
			"sender"=>$sender
		));
		$this->smarty->display("paotui_sender/auth.html");
	}
	public function onAdd(){
		$sender=MM("paotui","paotui_sender")->get(SENDERID);
		 
		$this->smarty->goAssign(array(
			"sender"=>$sender
		));
		$this->smarty->display("paotui_sender/add.html");
	}
	
	public function onSave(){
		$sender=MM("paotui","paotui_sender")->get(SENDERID);
		$nickname=post("nickname","h");
		$description=post("description","h");
		$address=post("address","h");
		MM("paotui","paotui_sender")->update(array(
			"nickname"=>$nickname,
			"description"=>$description,
			"address"=>$address
			 
		),"senderid=".SENDERID);
		$this->goAll("保存成功");
	}
	
	public function onAuthsave(){
		$truename=post("truename","h");
		$userno=post("userno","h");
		$usercard=post("usercard","h");
		$sender=MM("paotui","paotui_sender")->get(SENDERID);
		if($sender["isauth"]){
			$this->goAll("已经认证过了，无法修改，请联系客服",1);
		}
		MM("paotui","paotui_sender")->update(array(
			"truename"=>$truename,
			"userno"=>$userno,
			"usercard"=>$usercard,
			"nickname"=>$truename,
			"isauth"=>1
		),"senderid=".SENDERID);
		$this->goAll("认证成功");
		
	} 
	
	
}
?>