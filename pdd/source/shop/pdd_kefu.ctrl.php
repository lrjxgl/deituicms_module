<?php
class pdd_kefuControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$this->smarty->display("pdd_kefu/index.html");
	}
	
}