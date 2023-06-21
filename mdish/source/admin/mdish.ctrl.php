<?php
class mdishControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onMenu(){
		$this->smarty->goAssign(array(
			"a"=>1
		));
		$this->smarty->display("menu.html");
	}
	public function onDefault(){
		$this->smarty->goAssign(array(
			"a"=>1
		));
		$this->smarty->display("mdish/index.html");
	}
	
	 
}
?>