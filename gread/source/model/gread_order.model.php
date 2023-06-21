<?php 
class gread_orderModel extends model{
	public $table="mod_gread_order";
	public function __construct(){
		parent::__construct();
	}
	public function statusList(){
		return array(
			0=>"新订单",
			1=>"已确认",
			2=>"待收货",
			3=>"已完成",
			4=>"已取消"
		);
	}
}