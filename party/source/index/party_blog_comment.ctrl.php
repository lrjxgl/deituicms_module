<?php
class party_blog_commentControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
	}
	public function onMy(){
		
		$this->smarty->display("party_blog_comment/my.html");
	}
	
}