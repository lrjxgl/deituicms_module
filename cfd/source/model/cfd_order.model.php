<?php
class cfd_orderModel extends model{
	public $table="mod_cfd_order";
	public function __construct(){
		parent::__construct();
	}
	
	public function statuslist(){
		return array(
			0=>"未生效",
			1=>"已生效",
			4=>"已取消"
			
		);
	}
	
}
?>