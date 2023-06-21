<?php
class xuyuan_menuControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$this->smarty->display("menu.html");
	}
}
?>