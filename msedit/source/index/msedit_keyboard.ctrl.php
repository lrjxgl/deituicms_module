<?php
class msedit_keyboardControl extends skymvc{
	public function onDefault(){
		$this->smarty->display("msedit_keyboard/index.html");
	}
	
}