<?php
class fishing_freeControl extends skymvc{
	
	public function onDefault(){
		$data=M("dataapi")->getWord("增殖放流");
		$this->smarty->goAssign(array(
			"data"=>$data
		));
		$this->smarty->display("fishing_free/index.html");
	}
	
	
}
?>