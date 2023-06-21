<?php
class zblive_hoster_applyControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		M("login")->checkLogin();
	}
	public function onDefault(){
	 
		$userid=M("login")->userid;
		$row=M("mod_zblive_hoster_apply")->selectRow("userid=".$userid." AND status in(0,1) ");
		if($row){
			$this->goAll("你已经申请了",1);
		}
		$this->smarty->goAssign(array(
			"a"=>1
		));
		$this->smarty->display("zblive_hoster_apply/index.html");
		
	}
	public function onSave(){
		$userid=M("login")->userid;
		$row=M("mod_zblive_hoster_apply")->selectRow("userid=".$userid." AND status in(0,1) ");
		if($row){
			$this->goAll("你已经申请了",1);
		}
		$data=M("mod_zblive_hoster_apply")->postData();
		$data["userid"]=$userid;
		$data["dateline"]=time();
		M("mod_zblive_hoster_apply")->insert($data);
		$this->goAll("申请成功");
	}
}
?>