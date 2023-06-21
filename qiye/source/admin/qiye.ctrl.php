<?php
class qiyeControl extends skymvc{
	public function onDefault(){
		$this->smarty->display("qiye/index.html");
	}
	public function onMenu(){
		$this->smarty->display("menu.html");
	}
	
}