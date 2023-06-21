<?php
class group_typeModel extends model{
	public $table="mod_group_type";
	public function __construct(){
		parent::__construct();
	}
	
	public function getList($status=1){
		if($status){
			$where=" status=1 ";
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