<?php
class fsbuy_order_codeModel extends model{
	public $table="mod_fsbuy_order_code";
	public function __construct(){
		parent::__construct();
	}
	public function get($orderid,$shopid){
		$row=$this->selectRow("orderid=".$orderid);
		if(empty($row)){
			require "extends/hashids/Hashids.php";
			$hashids = new Hashids\Hashids('fsbuy_order_code',8,'abcdefghijklmnopqrstuvwxyz');
			$icode=$hashids->encode($orderid);
			 
			$this->insert(array(
				"orderid"=>$orderid,
				"shopid"=>$shopid,
				"ordercode"=>$icode
			));
			$row=$this->selectRow("orderid=".$orderid);	
		}
		 
		return $row["ordercode"];
	}
	
}