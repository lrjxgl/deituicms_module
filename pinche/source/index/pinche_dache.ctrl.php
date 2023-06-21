<?php
class pinche_dacheControl extends skymvc{
	
	public function onDefault(){
		
		$about=M("dataapi")->getWord("我要叫快车");
		$this->smarty->goAssign(array(
			"about"=>$about
		));
		$this->smarty->display("pinche_dache/index.html");
	}
	
}