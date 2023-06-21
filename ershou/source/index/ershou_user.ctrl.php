<?php
class ershou_userControl extends skymvc{
	
	public function onDefault(){
		M("login")->checkLogin();
		$userid=m("login")->userid;
		$user=M("user")->getUser($userid,"userid,nickname,user_head,description");
		//统计
		$fav_num=M("fav")->selectOne(array(
			"where"=>" userid=".$userid,
			"fields"=>" count(*) "
		));
		$history_num=M("mod_ershou_history")->selectOne(array(
			"where"=>" userid=".$userid,
			"fields"=>" count(*) "
		));
		$follow_num=M("follow")->selectOne(array(
			"where"=>" userid=".$userid,
			"fields"=>" count(*) "
		));
		$followed_num=M("followed")->selectOne(array(
			"where"=>" userid=".$userid,
			"fields"=>" count(*) "
		));
		$post_num=M("mod_group_title")->selectOne(array(
			"where"=>" userid=".$userid,
			"fields"=>" count(*) "
		));
		$this->smarty->goAssign(array(
			"user"=>$user,
			"fav_num"=>$fav_num,
			"history_num"=>$history_num,
			"follow_num"=>$follow_num,
			"followed_num"=>$followed_num,
			"post_num"=>$post_num
		));
		$this->smarty->display("ershou_user/index.html");
	}
	
}