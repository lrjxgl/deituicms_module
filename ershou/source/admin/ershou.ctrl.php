<?php
class ershouControl extends skymvc{
	public function onDefault(){
		
	}
	
	public function onMenu(){
		$this->smarty->display("menu.html");
	}
}