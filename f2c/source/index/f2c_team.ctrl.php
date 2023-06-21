<?php
class f2c_teamControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
	}
	public function onAdd(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$team=MM("f2c","f2c_team")->selectRow("userid=".$userid);
		if(empty($team)){
			$this->goAll("您还不是团长",1);
		}
		$this->smarty->goAssign(array(
			"team"=>$team
		));
		$this->smarty->display("f2c_team/add.html");
	}
	
}