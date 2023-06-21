<?php
class flk_userControl extends skymvc{
	
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
		//获取待报销的
		$flks=MM("flk","flk_queue")->statUser($userid);
		$money=MM("flk","flk_account")->get($userid);
		$this->smarty->goAssign(array(
			"data"=>$user,
			"navList"=>$navList,
			"flks"=>$flks["flks"],
			"flk_num"=>$flks["flk_num"],
			"money"=>$money
			 
		));
		$this->smarty->display("flk_user/index.html");
	}
	
	
}