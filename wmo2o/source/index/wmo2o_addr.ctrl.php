<?php
class wmo2o_addrControl extends skymvc{
	
	public function onDefault(){
		
		$this->smarty->display("wmo2o_addr/index.html");
	}
	
	public function onCity(){
		$this->smarty->display("wmo2o_addr/city.html");
	}
	
}