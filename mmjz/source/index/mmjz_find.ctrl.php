<?php
class mmjz_findControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$this->smarty->display("mmjz_find/index.html");
	}
	
}