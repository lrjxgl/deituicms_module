<?php
class cy2c_order_productModel extends model{
	public $table="mod_cy2c_order_product";
	public function __construct(){
		parent::__construct();
	}
	
	public function statusList(){
		return array(
			0=>"待接单",
			1=>"待制作",
			2=>"制作中",
			3=>"配送中",
			4=>"已完成",
			8=>"已取消"
		);
	}
}