<?php
class b2b_findControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$this->smarty->display("b2b_find/index.html");
	}
	
}