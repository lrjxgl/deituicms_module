<?php
class lltuan_groupModel extends model{
	public $table="mod_lltuan_group";
	public function getListByIds($ids,$fields="*"){
		$ids=array_unique($ids);
		if(empty($ids)){
			return [];
		}
		$res=$this->select(array(
			"where"=>" gid in("._implode($ids).")",
			"fields"=>$fields
		));
		$list=[];
		if(!empty($res)){
			foreach($res as $rs){
				$list[$rs["gid"]]=$rs;
			}
		}
		return $list;
	}
	
	public function Dselect($ops=array(),&$rscount=false){
		$list=$this->select($ops,$rscount);
		foreach($list as $k=>$v){
			$v["imgurl"]=images_site($v["imgurl"]);
			$list[$k]=$v;
		}
		return $list;
	}
	
}