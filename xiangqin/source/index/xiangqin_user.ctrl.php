<?php
class xiangqin_userControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$user=M("user")->getUser($userid,"userid,nickname,user_head,gold,grade,money,follow_num,followed_num,gender");
		$friend_num=M("friend")->selectOne(array(
			"where"=>"userid=".$userid,
			"fields"=>"count(*) as ct"
		));
		$user["friend_num"]=intval($friend_num);
		//导航
		$navList=M("navbar")->getListByGroup(7);
		 
		$this->smarty->goAssign(array(
			"user"=>$user,
			"navList"=>$navList
			 
		));
		$this->smarty->display("xiangqin_user/index.html");
	}
	
	
}