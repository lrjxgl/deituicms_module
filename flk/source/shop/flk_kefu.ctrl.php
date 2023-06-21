<?php
class flk_kefuControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$this->smarty->display("flk_kefu/index.html");
	}
	
}