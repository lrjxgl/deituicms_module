<?php
class cj1_userControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$userid=M("login")->userid;
		$user=M("user")->getUser($userid);
		$cjuser=MM("cj1","cj1_user")->get($userid);
		$user['gold']=$cjuser['gold'];
		$this->smarty->goAssign(array(
			"user"=>$user
		));
		$this->smarty->display("user/index.html");
	}
	
}
?>