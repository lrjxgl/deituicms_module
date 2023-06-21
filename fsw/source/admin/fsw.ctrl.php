<?php
class fswControl extends skymvc{
	public function onDefault(){
		$this->smarty->display("fsw/index.html");
	}
	public function onMenu(){
		$this->smarty->display("menu.html");
	}
}