<?php
class wmo2o_userControl extends skymvc{
	
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
		 
		$this->smarty->goAssign(array(
			"data"=>$user,
			"navList"=>$navList
			 
		));
		$this->smarty->display("wmo2o_user/index.html");
	}
	
	
}