<?php
class f2c_team_applyControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		M("login")->checkLogin();
	}
	public function onDefault(){
		$userid=M("login")->userid;
		$apply=M("mod_f2c_team_apply")->selectRow("userid=".$userid);
		$this->smarty->goAssign(array(
			"apply"=>$apply
		));
		$this->smarty->display("f2c_team_apply/index.html");
	}
	public function onSave(){
		$userid=M("login")->userid;
		$apply=M("mod_f2c_team_apply")->selectRow("userid=".$userid);
		if($apply){
			$this->goAll("您已经提交申请了，请等待通知",1);
		}
		$data=M("mod_f2c_team_apply")->postData();
		$chkarr=array("nickname","telephone","usercard","wxhao","address");
		foreach($chkarr as $v){
			if(empty($data[$v])){
				$this->goAll("请填写完整内容".$v,1);
			}
		}
		$data["userid"]=$userid;
		M("mod_f2c_team_apply")->insert($data);
		$this->goAll("申请成功，请等待审核");
	}
	
}