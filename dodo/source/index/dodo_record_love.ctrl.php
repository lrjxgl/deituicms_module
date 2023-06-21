<?php
class dodo_record_loveControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		M("login")->checkLogin();
	}
	public function onDefault(){
		 
	}
	public function onToggle(){
		$recordid=get("recordid","i");
		$userid=M("login")->userid;
		$record=M("mod_dodo_record")->selectRow("id=".$recordid);
		$row=M("mod_dodo_record_love")->selectRow("userid=".$userid." AND recordid=".$recordid);
		if($row){
			M("mod_dodo_record_love")->delete("id=".$row["id"]);
			M("mod_dodo_record")->changenum("love_num",-1,"id=".$recordid);
			$this->goAll("已取消鼓励",0,array(
				"action"=>"delete",
				"love_num"=>$record["love_num"]-1
			));
		}else{
			M("mod_dodo_record_love")->insert(array(
				"userid"=>$userid,
				"recordid"=>$recordid,
				"doid"=>$record["doid"]
			));
			M("mod_dodo_record")->changenum("love_num",1,"id=".$recordid);
			$this->goAll("感谢您的鼓励",0,array(
				"action"=>"add",
				"love_num"=>$record["love_num"]+1
			));
			
		}
	}
}