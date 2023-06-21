<?php
class pinche_userControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		M("login")->checkLogin();
		$user=M("login")->getUser();
		$this->smarty->goAssign(array(
			"user"=>$user
		));
		$this->smarty->display("pinche_user/index.html");
	}
	
}
?>