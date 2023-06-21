<?php
class sjsj_userControl extends skymvc{
	public function onDefault(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$user=M("user")->getUser($userid,"userid,nickname,money,user_head");
		 
		$this->smarty->goAssign(array(
			"user"=>$user,
			 
		));
		$this->smarty->display("sjsj_user/index.html");
	}
}