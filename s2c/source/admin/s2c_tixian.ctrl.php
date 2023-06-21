<?php
class s2c_tixianControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$this->smarty->display("s2c_tixian/index.html");
	}
	
}