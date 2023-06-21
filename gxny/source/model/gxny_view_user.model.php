<?php
class gxny_view_userModel extends model{
	public $table="mod_gxny_view_user";
	public function __construct(){
		parent::__construct();
	}
	public function add($shopid,$userid){
		$shopid=intval($shopid);
		$userid=intval($userid);
		if(!$shopid || !$userid) return false;
		$row=$this->selectRow(" shopid=".$shopid." AND userid=".$userid);
		if($row){
			 
			$this->update(array(
				"lasttime"=>date("Y-m-d H:i:s"),
			),"id=".$row['id']);
		}else{
			$this->insert(array(
				"shopid"=>$shopid,
				"userid"=>$userid,
				"lasttime"=>date("Y-m-d H:i:s"),
				"createtime"=>date("Y-m-d H:i:s"),
			));
			 
		}
	}
	
}