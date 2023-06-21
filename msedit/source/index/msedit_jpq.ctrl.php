<?php
class msedit_jpqControl extends skymvc{
	
	public function onDefault(){
		
		$this->smarty->display("msedit_jpq/index.html");
	}
	
}