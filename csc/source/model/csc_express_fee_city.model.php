<?php
class csc_express_fee_cityModel extends model{
	public $base;
	public function __construct(){
		parent::__construct();
		$this->base=$base;
		$this->table="mod_csc_express_fee_city";
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