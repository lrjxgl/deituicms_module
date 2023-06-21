<?php
class ershou_nearControl extends skymvc{
	public function onDefault(){
		
		$this->smarty->display("ershou_near/index.html");
	}
}