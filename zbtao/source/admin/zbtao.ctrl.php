<?php
class zbtaoControl extends skymvc{
	public function onDefault(){
		
		$this->smarty->display("zbtao/index.html");
	}
	public function onMenu(){
		$this->smarty->display("menu.html");
	}
}