<?php
class fsw_matchModel extends model{
	public $table="mod_fsw_match";
	public function Dselect($ops,&$rscount=false){
		$res=$this->select($ops,$rscount);
		if(!empty($res)){
			 
			foreach($res as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				 
				$res[$k]=$v;
			}
		}
		return $res;
	}
	
	public function getListByIds($ids,$fields="*"){
		if(empty($ids)){
			return false;
		}
		$ids=array_unique($ids);
		$res=$this->Dselect(array(
			"where"=>" mid in("._implode($ids).") ",
			"fields"=>$fields
		));
		$list=[];
		if(!empty($res)){
			foreach($res as $k=>$v){
				$list[$v["mid"]]=$v;
			}
		}
		return $list;
	}
	
}