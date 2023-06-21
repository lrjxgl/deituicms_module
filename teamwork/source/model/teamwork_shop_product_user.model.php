<?php
class teamwork_shop_product_userModel extends model{
	public $table="mod_teamwork_shop_product_user";
	public $roleList=array(
		0=>"普通人员",
		1=>"管理员",
		2=>"发起人",
		3=>"开发人员",
	);
	public function __construct(&$base=null){
		parent::__construct($base);
	}
	
	public function roleList(){
		return $this->roleList;
	}
	 
	 
	
}
?>