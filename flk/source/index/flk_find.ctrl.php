<?php
class flk_findControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$this->smarty->display("flk_find/index.html");
	}
	
}