<?php
class search_topicControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		
		
		$this->smarty->display("search_topic/index.html");
	}
	
}

?>