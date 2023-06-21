<?php
class pdd_findControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$this->smarty->display("pdd_find/index.html");
	}
	
}