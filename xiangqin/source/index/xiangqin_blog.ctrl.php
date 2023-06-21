<?php
class xiangqin_blogControl extends skymvc{
	
	public function onDefault(){
		$this->smarty->display("xiangqin_blog/index.html");
	}
	
	public function onShow(){
		$id=get("id","i");
		$this->smarty->goAssign(array(
			"id"=>$id
		));
		$this->smarty->display("xiangqin_blog/show.html");
	}
}