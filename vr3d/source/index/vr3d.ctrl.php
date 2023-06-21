<?php
class vr3dControl extends skymvc{
	
	public function onDefault(){
		 
		$this->smarty->display("vr3d/index.html");
	}
	
	public function onFuzhuang(){
		$this->smarty->display("vr3d/fuzhuang.html");
	}
	
	public function onXie(){
		$this->smarty->display("vr3d/xie.html");
	}
	
	public function onHello(){
		
		$this->smarty->display("vr3d/hello.html");
	}
	public function onBase(){
		$this->smarty->display("vr3d/base.html");
	}
	
}