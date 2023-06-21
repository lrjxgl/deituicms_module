<?php
class cj1_userModel extends model{
	 
	public $table;
	public function __construct(){
		parent::__construct();
		 
		$this->table="mod_cj1_user";
	}
	
	function get($userid){
		$row=$this->selectRow("userid=".$userid);
		if(!$row){
			$this->insert(array(
				"userid"=>$userid
			));
			$row=$this->selectRow("userid=".$userid);
		}
		return $row;
	}
	
	function addGold($option){
		$this->changenum("gold",$option['gold'],"userid=".$option['userid']);
		M("mod_cj1_user_goldlog")->insert(array(
			"dateline"=>time(),
			"gold"=>$option['gold'],
			"content"=>$option['content'],
			"typeid"=>$option['typeid'],
			"userid"=>$option['userid']
		));
		
	}
	
}
?>