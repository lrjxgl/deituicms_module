<?php
class car_soldControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
		$this->smarty->display("car_sold/index.html");
	}
	
}