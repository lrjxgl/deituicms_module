<?php
class im_clearControl extends skymvc{
	
	public function onDefault(){
		$this->smarty->display("im_clear/index.html");
	}
	
	public function onbored(){
		$time=time()-3600*24*3;
		M("mod_im_bored")->delete(" dateline<".$time);
		$this->goAll("清理完成");
	}
}