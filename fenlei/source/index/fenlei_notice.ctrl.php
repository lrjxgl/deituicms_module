<?php
class fenlei_noticeControl extends skymvc{
	public function onDefault(){
		M("login")->checkLogin();
		$this->smarty->display("fenlei_notice/index.html");
	}
	
	/**获取所有未读消息***/
	public function onGetAllNum(){
		$userid=M("login")->userid;
		if(empty($userid)){
			$this->goAll("error",1);
		}
		//通知
		$noticeNum=M("notice")->selectOne(array(
			"where"=>"userid=".$userid." AND status=0",
			 
			"fields"=>" count(*) as ct"
		));
		//系统
		$sysmsgNum=M("sysmsg_user")->selectOne(array(
			"where"=>"userid=".$userid." AND status=0",
			 
			"fields"=>" count(*) as ct"
		)); 
		 
		$num=$noticeNum+$sysmsgNum;
		$data=array(
			"noticeNum"=>intval($noticeNum),
			"sysmsgNum"=>intval($sysmsgNum),
			"num"=>$num
		);
		echo json_encode(array("error"=>0,"data"=>$data));
	}
	
}