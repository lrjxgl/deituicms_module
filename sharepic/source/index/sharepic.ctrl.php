<?php
class sharepicControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}

	public function onDefault(){
		$this->smarty->display("index.html");
	}
	public function onMy(){
		M("login")->checkLogin();
		$this->smarty->display("my.html");
	}
}

?>