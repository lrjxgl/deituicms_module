<?php
class paotui_orderModel extends model{
	public $table="mod_paotui_order";
	public function __construct(){
		parent::__construct();
	}
	 
	
	public function getStatus($status=0,$typeid=1){
		switch($status){
			case 0:
				return "待去办";			
				break;
			case 1:	
				return "办理中";
				break;
			case 2:
				return "待验收";
				break;
			case 3:
				return "已完成";
				break;
		}
	}
}

?>