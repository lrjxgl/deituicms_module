<?php
class party_userControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$user=M("user")->getUser($userid);
		$party_createnum=M("mod_party")->selectOne(array(
			"where"=>" userid=".$userid,
			"fields"=>"count(*) as ct "
		));
		$party_joinnum=M("mod_party_join")->selectOne(array(
			"where"=>" userid=".$userid,
			"fields"=>"count(*) as ct "
		));
		$this->smarty->goAssign(array(
			"user"=>$user,
			"party_createnum"=>intval($party_createnum),
			"party_joinnum"=>intval($party_joinnum)
		));
		$this->smarty->display("party_user/index.html");
		
	}
	
}