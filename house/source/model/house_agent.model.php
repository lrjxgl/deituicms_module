<?php
class house_agentModel extends model{
	public $table="mod_house_agent";
	public function get($userid,$fields="userid,truename,uhead,telephone"){
		$row=$this->selectRow(array(
			"where"=>" userid=".$userid,
			"fields"=>$fields
		));
		if($row){
			$row["uhead"]=images_site($row["uhead"]);
			return $row;
		}
	}
	public function Dselect($option,&$rscount=false){
		$data=$this->select($option,$rscount);
		if($data){
			foreach($data as $k=>$v){
				$v['uhead']=images_site($v['uhead']);
				
				$data[$k]=$v;
			}
		}
		return $data;
	}
	
}