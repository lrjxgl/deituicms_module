<?php
class fishing_free_adminControl extends skymvc{
	
	public function onDefault(){
		
	}
	public function onApplySave(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$placeid=post("placeid","i");
		$nickname=post("nickname","h");
		$telephone=post("telephone","h");
		$description=post("description","h");
		$row=M("mod_fishing_free_admin")->selectRow("userid=".$userid." AND placeid=".$placeid);
		if(!empty($row)){
			$this->goAll("你已经申请过了",1);
		}
		M("mod_fishing_free_admin")->insert(array(
			"userid"=>$userid,
			"placeid"=>$placeid,
			"nickname"=>$nickname,
			"telephone"=>$telephone,
			"description"=>$description,
			"createtime"=>date("Y-m-d H:i:s")
		));
		$this->goAll("申请成功，等待审核");
	}
	
}