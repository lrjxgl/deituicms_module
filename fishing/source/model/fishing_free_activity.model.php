<?php
class fishing_free_activityModel extends model{
	
	public $table="mod_fishing_free_activity";
	public function Dselect($ops,&$rscount=false){
		$res=$this->select($ops,$rscount);
		if(!empty($res)){
			foreach($res as $k=>$rs){
				$rs["imgurl"]=images_site($rs["imgurl"]);
				$res[$k]=$rs;
			}
		}
		return $res;
	}
	
	public function getListByIds($ids,$fields="*"){
		if(empty($ids)) return [];
		$ids=array_unique($ids);
		$ops=array(
			"where"=>" actid in("._implode($ids).") ",
			"fields"=>$fields
		);
		$res=$this->Dselect($ops);
		if(empty($res)){
			return [];
		}
		$list=[];
		foreach($res as $rs){
			$list[$rs["actid"]]=$rs;
		}
		return $list;
	}
	
}