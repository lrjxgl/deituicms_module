<?php
class gold_orderModel extends model{
	public $table="mod_gold_order";
	public $statusList=array(
		0=>"待确认",
		1=>"待发货",
		2=>"待收货",
		3=>"已完成"
	);
	public function __construct(){
		parent::__construct();
	}
	 
}