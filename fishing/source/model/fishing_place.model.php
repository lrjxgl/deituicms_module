<?php
class fishing_placeModel extends model{
	public $table="mod_fishing_place";
	public function Dselect($ops,&$rscount=false){
		$data=$this->select($ops,$rscount);
		if(!empty($data)){
			 
			foreach($data as $k=>$v){
				$v["tagsList"]=[];
				if(!empty($v["tags"])){
					$v["tagsList"]=explode(" ",$v["tags"]); 
				}
				
				$v["imgsList"]=[];
				if(!empty($v["imgsdata"])){
					$ims=explode(",",$v["imgsdata"]);
					foreach($ims as $imkey=>$imv){
						$ims[$imkey]=images_site($imv);
					}
					$v["imgsList"]= $ims;
				}
				$data[$k]=$v;
			}
		}
		return $data;
	}
	
	public function getListByIds($ids,$fields="*"){
		if(empty($ids)) return [];
		$ids=array_unique($ids);
		$ops=array(
			"where"=>" placeid in("._implode($ids).") ",
			"fields"=>$fields
		);
		$res=$this->Dselect($ops);
		if(empty($res)){
			return [];
		}
		$list=[];
		foreach($res as $rs){
			$list[$rs["placeid"]]=$rs;
		}
		return $list;
	}
	
}