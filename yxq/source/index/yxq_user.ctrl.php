<?php
class yxq_userControl extends skymvc{
	
	public function onDefault(){
		M("login")->checkLogin();
		$user=M("login")->getUser();
		$this->smarty->goAssign(array(
			"user"=>$user
		));
		$this->smarty->display("yxq_user/index.html");
	}
}