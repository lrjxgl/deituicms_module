<?php
class pinche_baocheControl extends skymvc{
	
	public function onDefault(){
		
		$data=M("dataapi")->getWord("包车");
		$this->smarty->goAssign(array(
			"data"=>$data
		));
		$this->smarty->display("pinche_baoche/index.html");
		
	}
	
}
?>