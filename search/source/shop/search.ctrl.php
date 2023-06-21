<?php
class searchControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
		$this->loadModuleModel("search",array("mod_search"));
	}
	
	public function onDefault(){
		$this->smarty->display("index.html");
	}
}

?>