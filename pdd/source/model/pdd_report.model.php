<?php
class pdd_reportModel extends model{
	public $table="mod_pdd_report";
	public function __construct(){
		
	}
	
	public function TypeList(){
		return array(
			1=>"商家刷单",
			2=>"商家价格高于店内价格",
			3=>"商家资质问题",
			4=>"其他"
		);
	}
	
}
?>