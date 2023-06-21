<?php
class ershou_groupControl extends skymvc{
	
	public function onDefault(){
		
		$this->smarty->display("ershou_group/index.html");
	}
}