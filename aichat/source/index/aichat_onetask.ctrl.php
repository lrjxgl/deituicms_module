<?php
class aichat_onetaskControl extends skymvc{
	public function onDefault(){
		
	}
	public function onNewUser(){
		//新用户领取token
		M("login")->checkLogin();
		$userid=M("login")->userid;
		//判定是否新用户
		$row=M("mod_aichat_onetask")->selectRow("userid=".$userid." AND taskname='new_user_token'");
		$isGet=0;
		if(!empty($row)){
			$isGet=1;
		}
		$this->smarty->goAssign(array(
			"isGet"=>$isGet
		));
		$this->smarty->display("aichat_onetask/newuser.html");
	}
	public function onnewuser_send(){
		//新用户领取token
		M("login")->checkLogin();
		$userid=M("login")->userid;
		//判定是否新用户
		$row=M("mod_aichat_onetask")->selectRow("userid=".$userid." AND taskname='new_user_token'");
		if(!empty($row)){
			$this->goAll("你已经领取过了",1);
		}
		M("mod_aichat_onetask")->begin();
		M("mod_aichat_onetask")->insert(array(
			"userid"=>$userid,
			"taskname"=>"new_user_token",
			"createtime"=>date("Y-m-d H:i:s")
		));
		$cfg=MM("aichat","aichat_config")->get();
		$num=$cfg["new_user_token"];
		MM("aichat","aichat_user")->addToken(array(
			"userid"=>$userid,
			"num"=>$num,
			"content"=>"新用户赠送".$num."个Token"
		));
		MM("aichat","aichat_user")->addVip(array(
			"userid"=>$userid,
			"num"=>1
		));
		M("mod_aichat_onetask")->commit();
		$this->goAll("领取成功");
	}
	 
	
}