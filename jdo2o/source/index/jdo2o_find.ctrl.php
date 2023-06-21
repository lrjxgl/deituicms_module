<?php
class jdo2o_findControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$this->smarty->display("jdo2o_find/index.html");
	}
	
}