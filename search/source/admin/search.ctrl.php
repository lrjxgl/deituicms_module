<?php
class searchControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
		$this->loadModuleModel("search",array("mod_search"));
	}
	
	public function onDefault(){
		$domains=M("mod_search_domain")->select();
		$this->smarty->assign(array(
			"domains"=>$domains
		));
		$this->smarty->display("search/index.html");
	}
	
	public function onMenu(){
		$this->smarty->display("search/menu.html");
	}
}

?>