<?php
class gread_olprintControl extends skymvc{
	
	public function onInit(){
		$a=get("a","h");
		$a=str_replace("//","",$a);
		if(empty($a) || $a=='default'){
			$a="index";
		}
		$this->smarty->display("gread_olprint/".$a.".html");
	}
	
	
}
