<?php
class aichat_open_xunfeiControl extends skymvc{
	
	public function onDefault(){
		$data=MM("aichat","aichat_open_xunfei")->get();
		$this->smarty->goAssign(array(
			"data"=>$data
		));
		$this->smarty->display("aichat_open_xunfei/index.html");
	}
	public function onSave(){
		$data=MM("aichat","aichat_open_xunfei")->postData();
		MM("aichat","aichat_open_xunfei")->update($data," 1 ");
		$this->goAll("保存成功");
	}
	
}