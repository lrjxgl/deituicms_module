<?php
class ttsControl extends skymvc{
	public function onDefault(){
		
	}
	public function onApi(){
		CC("tts","tts_qcloud")->onApi();
	}
}