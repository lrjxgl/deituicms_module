<?php
class youyaoControl extends skymvc{
	
	public function onDefault(){ 
		
		$this->smarty->display("youyao/index.html");
	}
}