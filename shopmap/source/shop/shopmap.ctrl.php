<?php
class shopmapControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}

	public function onDefault(){
		$this->smarty->display("index.html");
	}
}

?>