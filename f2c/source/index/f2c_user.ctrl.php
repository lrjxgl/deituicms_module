<?php
class f2c_userControl extends skymvc{
	
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
		$team=MM("f2c","f2c_team")->selectRow("userid=".$userid);  
		$this->smarty->goAssign(array(
			"data"=>$user,
			"navList"=>$navList,
			"team"=>$team
			 
		));
		$this->smarty->display("f2c_user/index.html");
	}
	
	
}