<?php
class pincheControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onMenu(){
		$this->smarty->display("menu.html");
	}
	public function onDefault(){
		
	}
	public function onMap(){
		$this->smarty->display("pinche/map.html");
	}
	
}