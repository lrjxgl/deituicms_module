<?php
class fsw_userModel extends model{
	public $table="mod_fsw_user";
	public function get($userid,$fields="*"){
		$row=$this->selectRow("userid=".$userid);
		if(empty($row)){
			$this->insert(array(
				"userid"=>$userid
			));
			$row=$this->selectRow($userid);
		}
		$row["user_head"]=images_site($row["user_head"]);
		return $row;
		
	}
	
	public function getListByIds($uids,$fields="*"){
		$uids=array_unique($uids);
		if(empty($uids)){
			return [];
		}
		$res=$this->select(array(
			"where"=>" userid in("._implode($uids).") ",
			"fields"=>$fields
		));
		$list=[];
		if(!empty($res)){
			foreach($res as $v){
				$v["user_head"]=images_site($v["user_head"]);
				$list[$v['userid']]=$v;
			}
		}
		return $list;
	}
	
	public function Dselect($ops,&$rscount=false){
		$res=$this->select($ops,$rscount);
		if(!empty($res)){
			foreach($res as $k=>$v){
				$v["user_head"]=images_site($v["user_head"]);
				$res[$k]=$v;
			}
		}
		return $res;
	}
	
}