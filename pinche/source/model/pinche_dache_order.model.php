<?php
class pinche_dache_orderModel extends model{
	public $table="mod_pinche_dache_order";
	public function statusList(){
		return array(
			0=>"未接单",
			1=>"送客中",
			2=>"送客完成",
			3=>"已完成",
			4=>"已取消"
		);
	}
}