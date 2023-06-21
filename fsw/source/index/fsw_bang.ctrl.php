<?php
class fsw_bangControl extends skymvc{
	public function onDefault(){
		
		$this->smarty->display("fsw_bang/index.html");
	}
	
	public function onPeople(){
		
		$list=MM("fsw","fsw_user")->Dselect(array(
			"where"=>" status =1 ",
			"order"=>" grade DESC",
			"limit"=>100
		));
		 
		$this->smarty->goAssign(array(
			"list"=>$list
		));
	}
	
}