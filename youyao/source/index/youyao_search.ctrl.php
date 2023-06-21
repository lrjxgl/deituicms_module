<?php
class youyao_searchControl extends skymvc{
	
	public function onDefault(){ 
		
		$this->smarty->display("youyao_search/index.html");
	}
}