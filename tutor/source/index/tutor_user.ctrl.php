<?php
class tutor_userControl extends skymvc{
	
	public function onDefault(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$user=M("user")->getUser($userid,"userid,nickname,money,user_head");
		$shop=M("mod_tutor_shop")->selectRow(array(
			"where"=>" userid=".$userid,
			"fields"=>"shopid,title"
		));
		$this->smarty->goAssign(array(
			"user"=>$user,
			"shop"=>$shop
		));
		$this->smarty->display("tutor_user/index.html");
	}
	
}
