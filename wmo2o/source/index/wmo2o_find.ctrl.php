<?php
class wmo2o_findControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$this->smarty->display("wmo2o_find/index.html");
	}
	
}