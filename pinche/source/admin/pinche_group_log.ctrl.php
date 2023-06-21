<?php
class pinche_group_logControl extends skymvc{
	
	public function onDefault(){
		$gid=get("gid","i");
		$list=M("mod_pinche_group_log")->select(array(
			"where"=>" gid=".$gid,
			"order"=>"id ASC"
		));
		$this->goAll("success",0,array(
			"list"=>$list
		));
	}
	
}