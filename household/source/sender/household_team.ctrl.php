<?php
class household_teamControl extends skymvc{
	public function onDefault(){
		
		$this->smarty->display("household_team/index.html");
	}
}
?>