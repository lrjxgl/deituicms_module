<?php
class house_agent_applyControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		M("login")->checkLogin();
	}
	public function onDefault(){
		
	}
	
	public function onAdd(){
		$userid=M("login")->userid;
		$data=M("mod_house_agent_apply")->selectRow("userid=".$userid);
		if($data){
			$data["true_uhead"]=images_site($data["uhead"]);
			$data["true_usercard"]=images_site($data["usercard"]);
		}
		$this->smarty->goAssign(array(
			"data"=>$data
		));
		$this->smarty->display("house_agent_apply/add.html");
	}
	public function onSave(){
		$userid=M("login")->userid;
		$row=M("mod_house_agent_apply")->selectRow("userid=".$userid);
		if($row){
			$this->goAll("你已经提交申请审核了",1);
		}
		$data=M("mod_house_agent_apply")->postData();
		
		$data["dateline"]=time();
		$data["userid"]=$userid;
		M("mod_house_agent_apply")->insert($data);
		$this->goAll("申请成功，请等待审核");
	}
	
}
