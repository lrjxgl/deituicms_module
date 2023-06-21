<?php
class fxa_shopModel extends model{
	
	public $table="mod_fxa_shop";
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
			"where"=>" shopid in("._implode($ids).")"
		));
		 $list=array();
		if($res){
			foreach($res as $rs){
				$list[$rs["shopid"]]=$rs;
			}
			
		}
		return $list;
	}
	 
}