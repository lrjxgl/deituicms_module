<?php
class f2c_userModel extends model{
	public $table="mod_f2c_user";
	public function __construct(){
		parent::__construct();
	}
	public function get($userid){
		$row=$this->selectRow("userid=".$userid);
		if(!$row){
			$this->insert(array(
				"userid"=>$userid
			));
			$row=array(
				"userid"=>$userid,
				"grade"=>0,
				"money"=>0
			);
		}
		return $row;
	}
	
	
	 
	public function getListByIds($uids){
		$res=$this->select(array(
			"where"=>" userid in("._implode($uids).") ",
			"fields"=>"team_money,userid"
		));
		if(!$res){
			return false;
		}
		$us=array();
		foreach($res as $v){
			$us[$v["userid"]]=$v;
		}
		return $us;
	}
	
}