<?php
class b2c_tplControl extends skymvc{
	
	public function onDefault(){
		$tplList=array(
			[
				"page"=>"b2c/index",
				"title"=>"首页",
				"diyui"=>[
					"md-abc-a",
					"md-abc-b"
				]
			]
		);
		$this->smarty->goAssign(array(
			"tplList"=>$tplList
		));
		$this->smarty->display("b2c_tpl/index.html");
	}
	
}