<?php
class printer_configControl extends skymvc{
	
	public function onDefault(){
		$config=MM("printer","printer_config")->get();
		 
		$this->smarty->goAssign(array(
			"pconfig"=>$config
		));
		$this->smarty->display("printer_config/index.html");
	}
	public function onSave(){
		$config=MM("printer","printer_config")->get();
		$data=MM("printer","printer_config")->postData();
		MM("printer","printer_config")->update($data,"id=".$config["id"]);
		$this->goAll("success");
	}
	
}