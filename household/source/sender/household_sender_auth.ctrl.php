<?php
class household_sender_authControl extends skymvc{
	public function onDefault(){
		$sender=MM("household","household_sender")->get(SENDERID);
		if($sender["isauth"]){
			$this->goAll("认证成功",0,0,"/sender.php?m=household");
		}
		$auth=M("mod_household_sender_auth")->selectRow("senderid=".SENDERID." AND status=0 ");
		 
		$this->smarty->goAssign(array(
			"sender"=>$sender,
			"auth"=>$auth
		));
		$this->smarty->display("household_sender_auth/index.html");
	}
	public function onSave(){
		$truename=post("truename","h");
		$userno=post("userno","h");
		$usercard=post("usercard","h");
		$sender=MM("household","household_sender")->get(SENDERID);
		if($sender["isauth"]){
			$this->goAll("已经认证过了，无法修改，请联系客服",1);
		}
		$row=MM("household","household_sender_auth")->selectRow("senderid=".SENDERID);
		if($row){
			MM("household","household_sender_auth")->update(array(
				"truename"=>$truename,
				"userno"=>$userno,
				"usercard"=>$usercard,
				"telephone"=>$sender["telephone"],
				 "status"=>0
			),"senderid=".SENDERID);
		}else{
			MM("household","household_sender_auth")->insert(array(
				"truename"=>$truename,
				"userno"=>$userno,
				"usercard"=>$usercard,
				"telephone"=>$sender["telephone"],  
				"senderid"=>SENDERID
			));
		}
		//通知网站
		M("site_msg")->add([
			"tablename"=>"household_sender_auth",
			"content"=>"有家政服务员工提交认证了快去审核吧"
		]);
		$this->goAll("认证提交成功，请等待审核");
	}
}