<?php 
class fenlei_commentControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
	}
	
	public function onMy(){
		
		$this->smarty->display("fenlei_comment/my.html");
	}
	
}