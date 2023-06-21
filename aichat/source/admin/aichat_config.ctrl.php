<?php
class aichat_configControl extends skymvc{
	public function onDefault(){
		$data=MM("aichat","aichat_config")->get();
		$this->smarty->goAssign(array(
			"data"=>$data
		));
		$this->smarty->display("aichat_config/index.html");
	}
	
	public function onSave(){
		$row=MM("aichat","aichat_config")->get();
		$data=MM("aichat","aichat_config")->postData();
		MM("aichat","aichat_config")->update($data,"id=".$row["id"]);
		$this->goAll("success");
	}
}