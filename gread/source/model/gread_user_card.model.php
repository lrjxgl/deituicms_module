<?php
class gread_user_cardModel extends model{
	public $table="mod_gread_user_card";
	public function __construct(&$base=null){
		parent::__construct($base);
	}
	
	
	public function get($userid){
		$guser=$this->selectRow("userid=".$userid);
		if(!$guser){
		 
			$this->insert(array(
				"userid"=>$userid,
				"createtime"=>date("Y-m-d H:i:s")
			));
			$guser=$this->selectRow("userid=".$userid);
		}
		return $guser;
	}
	
}
?>