<?php
class mdish_userControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		M("login")->checkLogin();
		$userid=M('login')->userid;
		$user=M("user")->getUser($userid);
		$this->smarty->goAssign(array(
			"user"=>$user
		));
		$this->smarty->display("mdish_user/index.html");
	}
	
	 
}
?>