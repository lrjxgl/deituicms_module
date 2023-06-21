<?php
class jdo2o_kefuControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$this->smarty->display("jdo2o_kefu/index.html");
	}
	
}