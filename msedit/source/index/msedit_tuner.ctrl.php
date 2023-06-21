<?php
class msedit_tunerControl extends skymvc{
	
	public function onDefault(){
		
		$this->smarty->display("msedit_tuner/index.html");
	}
	
}