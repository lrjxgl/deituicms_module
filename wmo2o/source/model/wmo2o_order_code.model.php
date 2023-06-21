<?php
class wmo2o_order_codeModel extends model{
	public $table="mod_wmo2o_order_code";
	public function __construct(){
		parent::__construct();
	}
	public function get($orderid,$shopid){
		$row=$this->selectRow("orderid=".$orderid);
		if(empty($row)){
			require "extends/hashids/Hashids.php";
			$hashids = new Hashids\Hashids('wmo2o_order_code',6,'abcdefghijklmnopqrstuvwxyz');
			$icode=$hashids->encode($orderid);
			$md5=$hashids->encode(rand(100000,999999));
			$this->insert(array(
				"orderid"=>$orderid,
				"shopid"=>$shopid,
				"ordercode"=>substr($icode.$md5,0,12)
			));
			$row=$this->selectRow("orderid=".$orderid);	
		}
		 
		return $row["ordercode"];
	}
	
}