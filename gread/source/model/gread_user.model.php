<?php
class gread_userModel extends model{
	public $table="mod_gread_user";
	public function __construct(&$base=null){
		parent::__construct($base);
	}
	
	
	public function get($userid){
		$guser=$this->selectRow("userid=".$userid);
		if(!$guser){
			$user=M("login")->getUser();
			$this->insert(array(
				"userid"=>$userid,
				"nickname"=>$user['nickname'],
				"user_head"=>$user['user_head'],
				"telephone"=>$user['telephone']
			));
			$guser=$this->selectRow("userid=".$userid);
		}
		return $guser;
	}
	
}
?>