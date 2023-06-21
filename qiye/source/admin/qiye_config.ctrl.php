<?php
class qiye_configControl extends skymvc{
	public function onDefault(){
		$data=MM("qiye","qiye_config")->get();
		$this->smarty->goAssign(array(
			"data"=>$data
		));
		$this->smarty->display("qiye_config/index.html");
	}
	public function onSave(){
		$data=MM("qiye","qiye_config")->postData();
		MM("qiye","qiye_config")->update($data," 1 ");
		$this->goAll("success");
	}
}