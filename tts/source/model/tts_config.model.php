<?php
class tts_configModel extends model{
	public $table="mod_tts_config";
	public $comList=array(
		1=>["key"=>"qcloud","val"=>"腾讯云语音"],
		2=>["key"=>"alyun","val"=>"阿里云语音"]
		
	);
	public function get(){
		$row=$this->selectRow(" 1 ");
		if(empty($row)){
			$this->insert(array(
				"com"=>"qcloud"
			));
			$row=$this->selectRow(" 1 ");
		}
		return $row;
	}
	public function getOpen(){
		$cfg=$this->get();
		$row=M("mod_tts_open")->selectRow("com='".$cfg['com']."' AND status=1 ");
		return $row;
	}
}