<?php
class olprint_kefuControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$this->smarty->display("olprint_kefu/index.html");
	}
	
}