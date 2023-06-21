<?php
class fxa_logControl extends skymvc{
	
	public function onDefault(){
		
	}
	public function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
	 
		$list=M("mod_fxa_log")->select(array(
			"where"=>"  userid=".$userid,
			"order"=>" id DESC"
		));
		$this->smarty->goAssign(array(
			"list"=>$list
		));
		$this->smarty->display("fxa_log/my.html");
	}
	
}