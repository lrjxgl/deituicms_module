<?php
class csc_findControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$this->smarty->display("csc_find/index.html");
	}
	
}