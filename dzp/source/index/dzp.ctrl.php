<?php
class dzpControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		
		$this->smarty->display("dzp/index.html");
	}
	
	 
	
}
?>