<?php
class bzy_userControl extends skymvc{
	public function onDefault(){
		M("login")->checkLogin();
		$user=M("login")->getUser();
		$this->smarty->goAssign(array(
			"user"=>$user
		));
		$this->smarty->display("bzy_user/index.html");
	}
}