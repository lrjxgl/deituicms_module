<?php
class paotui_kefuControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		
		$this->smarty->display("paotui_kefu/index.html");
	}
	
}
?>