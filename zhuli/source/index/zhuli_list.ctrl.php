<?php
class zhuli_listControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$where=" status=1 ";
		$limit=24;
		$data=MM("zhuli","zhuli")->Dselect(array(
			"where"=>$where,
			"limit"=>$limit,
			"order"=>" id DESC"
		));
		$this->smarty->goAssign(array(
			"data"=>$data
		));
		$this->smarty->display("zhuli_list/index.html");
	}
	
	public function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$user=M("login")->getUser();
		 
		$this->smarty->goAssign(array(
			"user"=>$user
		));
		$this->smarty->display("zhuli_list/my.html");
	}
	
}
?>