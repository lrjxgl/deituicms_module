<?php
class paotui_sender_applyControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		
		$this->smarty->display("paotui_sender_apply/index.html");
	}
	
	public function onSave(){
		$data=M("mod_paotui_sender_apply")->postData();
		if(empty($data["truename"])){
			$this->goAll("姓名不能为空",1);
		}
		
		if(empty($data["userno"])){
			$this->goAll("身份证号码不能为空",1);
		}
		if(!is_tel($data["telephone"])){
			$this->goAll("手机号码不符合要求",1);
		}
		if(M("mod_paotui_sender_apply")->selectRow("telephone='".$data["telephone"]."'")){
			$this->goAll("当前手机已经申请了",1);
		}
		if(M("mod_paotui_sender")->selectRow("telephone='".$data["telephone"]."'")){
			$this->goAll("当前手机已经申请了",1);
		}
		$data["status"]=0;
		$data["createtime"]=date("Y-m-d H:i:s");
		M("mod_paotui_sender_apply")->insert($data);
		$this->goAll("申请成功，请等待审核");
	}
	
}
?>