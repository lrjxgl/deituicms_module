<?php
class s2c_userControl extends skymvc{
	
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
		//是否是团长
		$team=MM("s2c","s2c_team")->selectRow("userid=".$userid);
		$shequ=[];
		if($team){
			$team["userhead"]=images_site($team["userhead"]);
			$shequ=M("mod_s2c_shequ")->selectRow("scid=".$team["scid"]);
		}
		$this->smarty->goAssign(array(
			"data"=>$user,
			"navList"=>$navList,
			"team"=>$team,
			"shequ"=>$shequ
			 
		));
		$this->smarty->display("s2c_user/index.html");
	}
	
	
}