<?php
class mseditControl extends skymvc{
	public function onDefault(){
		$this->smarty->display("msedit/index.html");
	}
}