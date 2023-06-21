<?php
class taoke_typeControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$typelist=MM("taoke","taoke_type")->getList();
		$this->smarty->goAssign(array(
			"typelist"=>$typelist
		));
		$this->smarty->display("taoke_type/index.html");
	}
	
}
?>