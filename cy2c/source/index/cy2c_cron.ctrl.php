<?php
class cy2c_cronControl extends skymvc{
	
	public function onDefault(){
		
	}
	public function onCart(){
		$time=date("Y-m-d H:i:s",time()-3600);
		M("mod_cy2c_cart")->delete(" createtime<'".$time."' ");
		echo "success";
	}
}