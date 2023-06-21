<?php
class gread_book_commentControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		
		$this->smarty->display("gread_book_comment/index.html");
	}
	
}
?>