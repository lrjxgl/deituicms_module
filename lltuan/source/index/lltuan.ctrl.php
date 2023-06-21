<?php
class lltuanControl extends skymvc{
	public function onDefault(){
		
		$this->smarty->display("lltuan/index.html");
	}
}