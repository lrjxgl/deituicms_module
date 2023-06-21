<?php
class vipcardControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}

	public function onDefault(){
		$this->onMy();
	}
	public function onApply(){
		$userid=M("login")->userid;
		$user=M("user")->getUser($userid);
		$card=MM("vipcard","vipcard")->selectRow("userid=".$userid);
		if($card){
			$this->goAll("您已经办理过了",1);
		}
		$this->smarty->goAssign(array(
			"user"=>$user,
			"card"=>$card
		));
		$this->smarty->display("vipcard/apply.html");
	}
	public function onApplySave(){
		$userid=M("login")->userid;
		$user=M("user")->getUser($userid);
		$card=MM("vipcard","vipcard")->selectRow("userid=".$userid);
		if($card){
			$this->goAll("您已经办理过了",1);
		}
		$data=M("mod_vipcard_apply")->postData();
		if(empty($data["telephone"])){
			$this->goAll("请填写电话",1);
		}
		$apply=M("mod_vipcard_apply")->selectRow("userid=".$userid." AND status=0");
		if($apply){
			$this->goAll("您已经申请过了，请等待审核",1);
		}
		
		$data["userid"]=$userid;
		$data["createtime"]=date("Y-m-d H:i:s");
		M("mod_vipcard_apply")->insert($data);
		$this->goAll("申请成功");
		
	}
	public function onMy(){
		$userid=M("login")->userid;
		$user=M("user")->getUser($userid);
		$card=MM("vipcard","vipcard")->selectRow("userid=".$userid);
		if(!$card){
			$apply=M("mod_vipcard_apply")->selectRow("userid=".$userid." ANd status=0 ");
		}
		$logList=M("mod_vipcard_log")->select(array(
			"where"=>" userid=".$userid,
			"order"=>" id DESC",
			"limit"=>100
		));
		$this->smarty->goAssign(array(
			"user"=>$user,
			"card"=>$card,
			"apply"=>$apply,
			"logList"=>$logList
		));
		$this->smarty->display("vipcard/my.html");
	}
}

?>