<?php
class b2c_clearControl extends skymvc{
	public function onDefault(){
		$this->smarty->display("b2c_clear/index.html");
	}
	
	public function onCart(){
		$time=time()-3600*24*7;
		M("mod_b2c_cart")->delete("createtime<'".$time."' ");
		$this->goAll("清理购物车成功");
	}
	
	public function onHistory(){
		$time=time()-3600*24*7;
		M("mod_b2c_history")->delete("createtime<'".$time."' ");
		$this->goAll("清理浏览记录成功");
	}
	
}