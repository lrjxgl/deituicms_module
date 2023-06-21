<?php
class fswControl extends skymvc{
	
	public function onDefault(){
		
		$this->smarty->display("fsw/index.html");
	}
}