<?php
class ttcj_orderModel extends model{
	public $table="mod_ttcj_order";
	public function statusList(){
		return array(
			0=>"待确认",
			1=>"待发货",
			2=>"待接收",
			3=>"已完成"
		);
	}
}