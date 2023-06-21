<?php
class fxa_productModel extends model{
	
	public $table="mod_fxa_product";
	public function __construct(){
		parent::__construct();
	}
	public function Dselect($option,&$rscount=false){
		$list=$this->select($option,$rscount);
		if($list){
			 
			foreach($list as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$list[$k]=$v;
			}
		}
		return $list;		
	}
	
	public function getListByIds($ids){
		$ids=array_unique($ids);
		$res=$this->Dselect(array(
			"where"=>" id in("._implode($ids).")"
		));
		 $list=array();
		if($res){
			foreach($res as $rs){
				$list[$rs["id"]]=$rs;
			}
			
		}
		return $list;
	}
	 
}