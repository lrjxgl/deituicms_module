<?php
class pinche_group_msgControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$gid=get("gid","i");
		$ops=array(
			"where"=>" gid=".$gid
		);
		$list=MM("pinche","pinche_group_msg")->Dselect($ops);
		$this->goAll("success",0,array(
			"list"=>$list
		));
	}
	
	public function onSave(){
		$userid=M("login")->userid;
		$gid=post("gid","i");
		$content=post("content","h");
		M("mod_pinche_group_msg")->insert(array(
			"userid"=>$userid,
			"gid"=>$gid,
			"content"=>$content,
			"dateline"=>time()
		));
		$this->goAll("保存成功");
	}
	
}