<?php
class heguanControl extends skymvc{
	public function onDefault(){
		$this->smarty->display("heguan/index.html");
	}
}