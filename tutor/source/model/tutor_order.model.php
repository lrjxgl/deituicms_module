<?php
class tutor_orderModel extends model{
	public $table="mod_tutor_order";
	public function statusList(){
			$statusList=array(
				0=>"未接单",
				1=>"已接单",
				2=>"对接中",
				3=>"已完成",
				4=>"已取消"
			);
			return $statusList;
		}
}