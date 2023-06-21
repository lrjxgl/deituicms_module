<?php
class xiangqin_partyControl extends skymvc{
	
	public function onDefault(){
		
		$this->smarty->goAssign(array(
			"e"=>1
		));
		$this->smarty->display("xiangqin_party/index.html");
	}
	
}
?>