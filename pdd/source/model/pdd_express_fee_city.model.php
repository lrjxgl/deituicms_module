<?php
class pdd_express_fee_cityModel extends model{
	 
	public function __construct(){
		parent::__construct();
		 
		$this->table="mod_pdd_express_fee_city";
	}
	
	public function getCityIds($opt){
		$d=$this->select($opt);
		if($d){
			foreach($d as $v){
				$data[]=$v['areaid'];
			}
			return $data;
		}
	}
	
 
	
	 
	
	 
}

?>