<?php
class taoke_userControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$user=M("login")->getUser();
		 
		$invite_num=M("user")->selectOne(array(
			"where"=>" invite_userid=".$userid,
			"fields"=>"count(*)"
		));
		$user_money=MM("taoke","taoke_user_money")->get($userid);
		$this->smarty->goAssign(array(
			"user"=>$user,
			"invite_num"=>$invite_num,
			"invite_money"=>$user_money["income"],
			"user_money"=>$user_money
		));	
		$this->smarty->display("taoke_user/index.html");
	}
	
	public function oninvite(){
		
		$this->smarty->display("taoke_user/invite.html");
	}
}