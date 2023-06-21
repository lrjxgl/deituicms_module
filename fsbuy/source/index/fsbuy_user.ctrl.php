<?php
class fsbuy_userControl extends skymvc{
	
	public function onDefault(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$user=M("user")->getUser($userid,"userid,user_head,nickname,gold,money");
		$this->smarty->goAssign(array(
			"user"=>$user
		));
		$this->smarty->display("fsbuy_user/index.html");
	}
}
