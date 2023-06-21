<?php
class ershou_searchControl extends skymvc{
	public function onDefault(){
		$keyword=get("keyword","h");
		$this->smarty->goAssign(array(
			"keyword"=>$keyword
		));
		$this->smarty->display("ershou_search/index.html");
	}
}