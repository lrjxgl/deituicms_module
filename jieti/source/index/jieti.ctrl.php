<?php
class jietiControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}

	public function onDefault(){
		$option=array(
			"where"=>" isanswer=1 AND status=2 ",
			"order"=>"askid DESC"
		);
		$askList=MM("jieti","jieti_ask")->Dselect($option);
		$this->smarty->goAssign(array(
			"askList"=>$askList,
			"adImg"=>images_site("/static/images/mjietilogo.png")
		));
		$this->smarty->display("index.html");
	}
	
	public function onUser(){
		
		$this->smarty->display("jieti/user.html");
	}
}

?>