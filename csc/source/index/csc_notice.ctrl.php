<?php
class csc_noticeControl extends skymvc{
	public function onDefault(){
		
		$this->smarty->display("csc_notice/index.html");
	}
}