<?php
class gread_shop_outorderControl extends skymvc{
	
	public function __consrtuct(){
		parent::__consrtuct();
	}
	
	public function onDefault(){
		
		$this->smarty->display("gread_shop_outorder/index.html");
	}
	
}
?>