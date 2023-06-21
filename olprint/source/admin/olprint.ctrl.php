<?php
class olprintControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		
		$this->smarty->display("olprint/index.html");
	}
	
	public function onMenu(){
		$this->smarty->display("menu.html");
	}
	
}