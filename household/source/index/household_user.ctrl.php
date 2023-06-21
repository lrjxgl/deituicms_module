<?php
class household_userControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$user=M("user")->selectRow(array("where"=>" userid=".$userid));
		$user['user_head']=images_site($user['user_head']);
		//导航
		$navList=M("navbar")->getListByGroup(7);
		$invitecode=M("user_invitecode")->getCode($userid); 
		//
		$hhconfig=M("mod_household_config")->selectRow("1");
		$this->smarty->goAssign(array(
			"data"=>$user,
			"navList"=>$navList,
			"invitecode"=>$invitecode,
			"hhconfig"=>$hhconfig
			 
		));
		$this->smarty->display("household_user/index.html");
	}
	
	
}