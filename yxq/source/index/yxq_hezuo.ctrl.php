<?php
class yxq_hezuoControl extends skymvc{
	
	public function onDefault(){
		
		$this->smarty->display("yxq_hezuo/index.html");
	}
}
