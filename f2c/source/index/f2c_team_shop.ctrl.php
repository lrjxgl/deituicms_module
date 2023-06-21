<?php
class f2c_team_shopControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$team=M("mod_f2c_team")->selectRow("teamid=".TEAMID);
		$team["userhead"]=images_site($team["userhead"]);
		$this->smarty->goAssign(array(
			"team"=>$team
		));
		$this->smarty->display("f2c_team_shop/index.html");
	}
	
}
?>