<?php
class exam_userControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$userid=M("login")->userid;
		$user=M("user")->getUser($userid);
		$exam_num=M("mod_exam")->selectOne(array(
			"where"=>" userid=".$userid,
			"fields"=>" count(*) "
		));
		$this->smarty->goAssign(array(
			"user"=>$user,
			"exam_num"=>$exam_num
		));
		$this->smarty->display("exam_user/index.html");
	} 
	
}