<?php
class bzy_eventModel extends model{
	public $table="mod_bzy_event";
	
	
	public function getListByIds($ids){
		$ids=array_unique($ids);
		if(empty($ids)) return [];
		$res=$this->select(array(
			"where"=>" eventid in("._implode($ids).") "
		));
		$list=[];
		if(!empty($res)){
			foreach($res as $rs){
				$rs["imgurl"]=images_site($rs["imgurl"]);
				$list[$rs["eventid"]]=$rs;
			}
		}
		return $list;
	}
	
}