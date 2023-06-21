<?php
class im_group_user_applyControl extends skymvc{
	
	public function onDefault(){
		
	}
	public function onApplySave(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$groupid=post("groupid",'i');
		$content=post("content","h");
		$row=M("mod_im_group_user_apply")->selectRow("userid=".$userid." AND groupid=".$groupid);
		if(!empty($row)){
			$this->goAll("已经申请过了",1);
		}
		M("mod_im_group_user_apply")->insert(array(
			"userid"=>$userid,
			"groupid"=>$groupid,
			"content"=>$content,
			"dateline"=>time()
		));
		
		$this->goAll("success",0);
	}
}