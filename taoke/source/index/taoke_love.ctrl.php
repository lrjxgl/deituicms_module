<?php
class taoke_loveControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
	}
	public function onToggle(){
		$k=get("k","h");
		$productid=get("productid","i");
		$userid=M("login")->userid;
		$row=M("mod_taoke_love")->selectRow("userid=".$userid." AND productid=".$productid." AND k='".$k."' ");
		if($row){
			M("mod_taoke_love")->delete("id=".$row["id"]);
			M("mod_taoke_searchcache")->changenum("love_num",-1,"objectid=".$productid);
			$action="delete";
		}else{
			M("mod_taoke_love")->insert(array(
				"userid"=>$userid,
				"productid"=>$productid,
				"k"=>$k
			));
			M("mod_taoke_searchcache")->changenum("love_num",1,"objectid=".$productid);
			$action="add";
		}
		$this->goAll("success",0,array(
			"action"=>$action
		));
	}
	
}