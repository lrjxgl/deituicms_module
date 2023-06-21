<?php
class greadControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	
	public function onMenu(){
		$this->smarty->display("menu.html");
	}
	public function onDefault(){
		
	}
	
	
}
?>