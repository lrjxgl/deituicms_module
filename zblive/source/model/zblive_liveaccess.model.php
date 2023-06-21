<?php
class zblive_liveaccessModel extends model{
	public $table="mod_zblive_liveaccess";
	public function __construct(){
		parent::__construct();
	}
	public function get($userid){
		$row=M("mod_zblive_hoster")->selectRow("userid=".$userid);
		if($row && $row["status"]==1){
			return true;
		}
	}
}