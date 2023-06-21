<?php
class csc_supplierModel extends model{
	public $table="mod_csc_supplier";
	public function __construct(){
		parent::__construct();
	}
	public function getListByIds($ids){
		$ids=array_unique($ids);
		$res=$this->select(array(
			"where"=>" supid in("._implode($ids).")"
		));
		if($res){
			$list=array();
			foreach($res as $rs){
				$list[$rs["supid"]]=$rs;
			}
			return $list;
		}
	}
}