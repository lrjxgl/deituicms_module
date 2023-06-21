<?php
class qiye_configControl extends skymvc{
	
	public function onDefault(){
		$qyConfig=MM("qiye","qiye_config")->get();
		$this->goAll("success",0,array(
			"qyConfig"=>$qyConfig
		));
	}
}
?>