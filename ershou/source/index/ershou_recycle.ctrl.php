<?php
class ershou_recycleControl extends skymvc{
	
	public function onDefault(){
		
		$this->smarty->display("ershou_recycle/index.html");
	}
	
}