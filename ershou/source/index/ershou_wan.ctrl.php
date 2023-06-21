<?php
class ershou_wanControl extends skymvc{
	
	public function onDefault(){
		
		$this->smarty->display("ershou_wan/index.html");
	}
	
}