<?php
class mod_vote_joinModel extends model{
	 
	public function __construct(){
		parent::__construct();
		 
		$this->table="mod_vote_join";
	}
	
	public function getListByIds($ids){
		if(empty($ids)) return false;
		$d=$this->select(array("where"=>" id in("._implode($ids).")"));
		if($d){
			foreach($d as $v){
				$data[$v['id']]=$v;
			}
			return $data;
		}
		return false;
	}
}

?>