<?php
class taoke_orderModel extends model{
	
	public $table="mod_taoke_order";
	public function statusList(){
		$list=array(
			0=>"待确认",
			1=>"待结算"
		);
	}
	
}
