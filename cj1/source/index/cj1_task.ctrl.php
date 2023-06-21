<?php
class cj1_taskControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$data=M("mod_cj1")->select(array(
			"where"=>" isfinish=0 AND isgold=1 AND status=1 ",
			"order"=>" starttime ASC",
			"limit"=>20
		));
		 
		$this->smarty->goAssign(array(
			"data"=>$data,
			"userid"=>M("login")->userid
		));
		$this->smarty->display("task/index.html");	
	}
	
	public function onMy(){
		$data=M("mod_cj1")->select(array(
			"where"=>" isfinish=0 AND isgold=1 ",
			"order"=>" starttime ASC",
			"limit"=>20
		));
		$this->smarty->goAssign(array(
			"data"=>$data
		));
		$this->smarty->display("task/my.html");
	}
	
}
?>