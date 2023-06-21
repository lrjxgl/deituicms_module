<?php
class msedit_soundControl extends skymvc{
	
	public function onDefault(){
		
		
	} 
	public function onLoadSound(){
		$sound=get("sound","h");
		$yy=get("yy","h");
		$file=ROOT_PATH."/module/msedit/themes/index/audio/".$sound."/".$yy.".mp3";
		echo file_get_contents($file);
	}
}