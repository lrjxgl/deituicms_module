<?php
class household_kefuControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		
		$this->smarty->display("household_kefu/index.html");
	}
	
}
?>