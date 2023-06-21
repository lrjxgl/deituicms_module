<?php
class jdo2o_shop_commissionModel extends model{
	public $table="mod_jdo2o_shop_commission";
	public function __construct(){
		parent::__construct();
	}
	public function get($shopid){
		$row=$this->selectRow("shopid=".$shopid);
		if(!$row){
			$row=array(
				"shopid"=>$shopid,
				"per"=>10,
				"stime"=>time(),
				"etime"=>time()
			);
			$this->insert($row);
		}
		return $row;
	}
	
}