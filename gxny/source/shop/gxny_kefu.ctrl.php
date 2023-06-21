<?php
class gxny_kefuControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$this->smarty->display("gxny_kefu/index.html");
	}
	
}