<?php
class test_movieControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$this->smarty->display("test_movie/index.html");
	}
	
}