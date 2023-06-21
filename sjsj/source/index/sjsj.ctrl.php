<?php
class sjsjControl extends skymvc{
	
	public function onDefault(){
		
		$this->smarty->display("sjsj/index.html");
	}
}