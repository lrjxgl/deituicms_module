<?php
class jdo2o_blog_commentControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
	}
	public function onMy(){
		
		$this->smarty->display("jdo2o_blog_comment/my.html");
	}
	
}