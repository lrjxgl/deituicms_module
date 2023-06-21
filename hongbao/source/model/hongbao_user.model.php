<?php
class hongbao_userModel extends model{
	public $table="mod_hongbao_user";
	public function __construct(){
		 
		
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
	
	function addmoney($option){
		$this->get($option['userid']);
		$this->changenum("money",$option['money'],"userid=".$option['userid']);
		M("mod_hongbao_user_moneylog")->insert(array(
			"dateline"=>time(),
			"money"=>$option['money'],
			"content"=>$option['content'],
			"typeid"=>$option['typeid'],
			"userid"=>$option['userid']
		));
		
	}
}
?>