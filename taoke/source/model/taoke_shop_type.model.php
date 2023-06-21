<?php
class taoke_shop_typeModel extends model{
	public $table="mod_taoke_shop_type";
	public function __construct(&$base=null){
		parent::__construct($base);
	}
	
	public function getList($status=2){
		if($status){
			$where=" status=2 ";
		}else{
			$where=" status<11 ";
		}
		$data=$this->select(array(
			"where"=>$where
		));
		if($data){
			foreach($data as $v){
				$sdata[$v['catid']]=$v;
			}
			return $sdata;
		}
		
	}
	
}
?>