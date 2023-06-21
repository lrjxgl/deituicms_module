<?php
class cfd_rewardModel extends model{
	public $table="mod_cfd_reward";
	public function __construct(){
		parent::__construct();
	}
	
	public function getListByIds($ids,$fields="*"){
		$tds=$this->select(array(
			"where"=>" id in("._implode($ids).")",
			"fields"=>$fields 
		));
		if($tds){
			foreach($tds as $td){
				$data[$td['id']]=$td;
			}
			return $data;
		}
		
	}
	
}
?>