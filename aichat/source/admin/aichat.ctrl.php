<?php
class aichatControl extends skymvc{
	public function onDefault(){
		
	}
	public function onMenu(){
		$this->smarty->display("menu.html");
	}
	
}