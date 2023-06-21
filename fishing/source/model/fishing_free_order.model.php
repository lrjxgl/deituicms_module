<?php
class fishing_free_orderModel extends model{
	public $table="mod_fishing_free_order";
	
	public function order($orderid){
		$row=$this->selectRow("orderid=".$orderid);
		$this->update(array(
			"ispay"=>1
		),"orderid=".$orderid);
		 
		MM("fishing","fishing_free_account")->add(array(
			"placeid"=>$row["placeid"],
			"money"=>$row["money"],
			"content"=>"获得".$row["money"]."元捐赠"
		));
		 
		
	}
	
}