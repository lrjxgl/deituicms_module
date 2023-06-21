<?php
class lltuan_placeModel extends model{
	public $table="mod_lltuan_place";
	public function getListByIds($ids,$fields="*"){
		$ids=array_unique($ids);
		if(empty($ids)){
			return [];
		}
		$res=$this->select(array(
			"where"=>" placeid in("._implode($ids).")",
			"fields"=>$fields
		));
		$list=[];
		if(!empty($res)){
			foreach($res as $rs){
				$list[$rs["placeid"]]=$rs;
			}
		}
		return $list;
	}
}