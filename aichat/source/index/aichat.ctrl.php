<?php
class aichatControl extends skymvc{
	public function onDefault(){
		
		$this->smarty->display("aichat_img/index.html");
	}
	
	public function onPrompt(){
		$list=M("mod_aichat_prompt")->select(array(
			"where"=>" status=1 "
		));
		$this->goAll("sucess",0,array(
			"list"=>$list
		));
	}
	
}