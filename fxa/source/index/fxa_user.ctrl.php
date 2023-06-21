<?php
class fxa_userControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$user=M("user")->getUser($userid,"userid,nickname,money,user_head");
		$orderNum=M("mod_fxa_order")->selectOne(array(
			"where"=>"userid=".$userid." AND ispay=1",
			"fields"=>"count(*) as ct"
		));
		$fxa_user=MM("fxa","fxa_user")->get($userid);
		$this->smarty->goAssign(array(
			"user"=>$user,
			"orderNum"=>$orderNum,
			"fxa_user"=>$fxa_user
		));
		$this->smarty->display("fxa_user/index.html");
	}
	
}