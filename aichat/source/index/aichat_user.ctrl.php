<?php
class aichat_userControl extends skymvc{
	
	public function onDefault(){
		M("login")->checklogin();
		$userid=M("login")->userid;
		$user=M("user")->getUser($userid,"userid,nickname,user_head,grade,gold,description");
		$aiuser=MM("aichat","aichat_user")->get($userid);
		$aiuser["vip_etime_date"]=date("Y-m-d H:i:s",$aiuser["vip_etime"]);
		$this->smarty->goAssign(array(
			"user"=>$user,
			"aiuser"=>$aiuser
		));
		$this->smarty->display("aichat_user/index.html");
	}
	
}