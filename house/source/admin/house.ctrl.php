<?php
class houseControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		
	}
	
	public function onMenu(){
		$this->smarty->display("menu.html");
	}
	
}
?>