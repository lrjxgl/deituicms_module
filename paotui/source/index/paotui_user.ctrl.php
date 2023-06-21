<?php
class paotui_userControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$user=M("user")->getUser($userid,"userid,nickname,user_head,money");
		$this->smarty->goAssign(array(
			"user"=>$user
		));
		$this->smarty->display("paotui_user/index.html");
	}
	
}
?>