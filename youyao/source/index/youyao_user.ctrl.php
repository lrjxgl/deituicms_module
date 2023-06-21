<?php
class youyao_userControl extends skymvc{
	
	public function ondefault(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$user=M("user")->getUser($userid,"userid,nickname,user_head,gold,grade,money,follow_num,followed_num,gender");
		$shop=MM("youyao","youyao_shop")->getShopByUserid($userid);
		 
		 
		$this->smarty->goAssign(array(
			"user"=>$user,
			"shop"=>$shop 
			 
		));
		$this->smarty->display("youyao_user/index.html");
	}
	
}
?>