<?php
class tts_configControl extends skymvc{
	public function onDefault(){
		$data=MM("tts","tts_config")->get();
		$comList=MM("tts","tts_config")->comList;
		$this->smarty->goassign(array(
			"data"=>$data,
			"comList"=>$comList
		));
		$this->smarty->display("tts_config/index.html");
	}
	public function onsave(){
		$data=MM("tts","tts_config")->postData();
		MM("tts","tts_config")->update($data," 1 ");
		$this->goAll("sucess");
	}
}