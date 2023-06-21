<?php
class s2c_doneControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		M("login")->checkLogin();
	}
	public function onDefault(){
		$userid=M("login")->userid;
		$team=MM("s2c","s2c_team")->selectRow("userid=".$userid);
		if(empty($team)){
			$this->goAll("您还不是团长",1);
		}
		$list=MM("s2c","s2c_done")->select(array(
			"where"=>" teamid=".$team["teamid"],
			"order"=>"id DESC"
		));
		
		$this->smarty->goAssign(array(
			"team"=>$team,
			"list"=>$list
		));
		$this->smarty->display("s2c_done/index.html");
	}
}