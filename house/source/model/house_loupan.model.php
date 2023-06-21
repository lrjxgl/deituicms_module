<?php
class house_loupanModel extends model{
	
	public $table="mod_house_loupan";
	public function __construct(){
		parent::__construct();
	}
	
	
	public function Dselect($option,&$rscount=false){
		$data=M("mod_house_loupan")->select($option,$rscount);
		if($data){
			foreach($data as $k=>$v){
				$v['imgurl']=images_site($v['imgurl']);
				$v["isbuy_title"]=$v["isbuy"]==1?'在售':'停售';
				$data[$k]=$v;
			}
		}
		return $data;
	}
	public function getListByIds($ids){
		$ids=array_unique($ids);
		$res=$this->Dselect(array(
			"where"=>" id in("._implode($ids).") "
		));
		if($res){
			foreach($res as $rs){
				$list[$rs["id"]]=$rs;
			}
			return $list;
		}
	}
	
}