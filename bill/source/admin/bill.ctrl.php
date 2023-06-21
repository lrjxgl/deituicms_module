<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class billControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		 
		public function onDefault(){
			 
		}
		
		public function onMenu(){
			
			$this->smarty->display("menu.html");
		}
		 
		 
		
		
	}

?>