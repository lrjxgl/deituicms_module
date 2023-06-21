<?php
class xiangqin_people_newModel extends model{
	public $table="mod_xiangqin_people_new";
	public function Dselect($ops,&$rscount=false){
		$res=$this->select($ops,$rscount);
	 
		if(!empty($res)){
			foreach($res as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$v["gender_title"]=$v["gender"]==1?'男':'女';
				$v["age"]=date("Y")-substr($v["birthday"],0,4)+1;
				$res[$k]=$v;
			}
		}
		return $res;
	}
	
	public function getListByUids($uids,$fields="*"){
		if(empty($uids)) return [];
		$uids=array_unique($uids);
		$res=$this->Dselect(array(
			"where"=>" userid in("._implode($uids).") ",
			"fields"=>$fields
		));
		
		$list=[];
		if(!empty($res)){
			
			foreach($res as $rs){
				$list[$rs["userid"]]=$rs;
			}
		}
		return $list;
	}
}