<?php
class pinche_driver_applyControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		M("login")->checkLogin();
	}
	public function onDefault(){
		$data=M("dataapi")->getWord("拼车招募司机");
		$userid=M("login")->userid;
		$driver=M("mod_pinche_driver")->selectRow("userid=".$userid);
		$canApply=true;
		if($driver){
			$canApply=false;
		}
		$apply=M("mod_pinche_driver_apply")->selectRow("userid=".$userid);
		if($apply["status"]==0){
			$canApply=false;
		}
		$this->smarty->goAssign(array(
			"data"=>$data,
			"canApply"=>$canApply,
			"apply"=>$apply,
			"driver"=>$driver
		));
		$this->smarty->display("pinche_driver_apply/index.html");
	}
	
	public function onSave(){
		$userid=M("login")->userid;
		$data=M("mod_pinche_driver_apply")->postData();
		foreach($data as $v){
			if(empty($v)){
				$this->goAll("请填写完整信息",1);
			}
		}
		$driver=M("mod_pinche_driver")->selectRow("userid=".$userid);
		if($driver){
			$this->goAll("你已经成为平台司机了",1);
		}
		$data["dateline"]=time();
		$row=M("mod_pinche_driver_apply")->selectRow("userid=".$userid);
		if($row){
			if($row["status"]==0){
				$this->goAll("你已经提交过信息了，请等待审核",1);
			}else{
				M("mod_pinche_driver_apply")->update($data,"id=".$row["id"]);
			}
			
		}else{
			$data["userid"]=$userid;
			
			M("mod_pinche_driver_apply")->insert($data);
		}
		
		
		$this->goAll("申请提交成功，请等待审核");
	}
	
}