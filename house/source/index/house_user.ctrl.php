<?php
class house_userControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		M("login")->checkLogin();
	}
	public function onDefault(){
		$user=M("login")->getUser();
		$userid=M("login")->userid;
		$agent_apply=true;
		$agent=M("mod_house_agent")->selectRow(array(
			'where'=>"userid=".$userid,
			"fields"=>"userid,status"
		));
		if($agent){
			$agent_apply=false;
		}
		if($agent_apply){
			$apply=M("mod_house_agent_apply")->selectRow(array(
				'where'=>"userid=".$userid,
				"fields"=>"userid,status"
			));
			if($apply){
				$agent_apply=false;
			}
		}
		
		$this->smarty->goAssign(array(
			"user"=>$user,
			"agent"=>$agent,
			"agent_apply"=>$agent_apply
		));
		$this->smarty->display("house_user/index.html");
	}
}
?>