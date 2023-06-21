<?php
class s2c_noticeControl extends skymvc{
	public function onDefault(){
		
		$this->smarty->display("s2c_notice/index.html");
	}
}