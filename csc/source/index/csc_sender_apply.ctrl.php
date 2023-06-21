<?php
class csc_sender_applyControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
		$this->smarty->display("csc_sender_apply/index.html");
	}
	
	public function onSave(){
		$data=M("mod_csc_sender")->postData();
		$row=M("mod_csc_sender")->selectRow("telephone='".$data['telephone']."'");
		if($row){
			$this->goAll("该手机号已经注册了",1);
		}
		$senderid=M("mod_csc_sender")->insert($data);
		$this->goAll("申请成功,请等待审核");
	}
}
?>