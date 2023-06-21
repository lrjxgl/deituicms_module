<?php
class tts_qcloudControl extends skymvc{
	public function onDefault(){
	}
	public function onApi(){	
		
		$word=get_post("word","x");
		if(empty($word)){
			$word="你好，欢迎使用得推CMS";
		}
		$word=strip_tags($word);
		$m5=md5($word);
		$key=strlen($word).$m5;
		$dir="attach/tts/".substr($m5,0,1)."/".substr($m5,0,2);
		umkdir($dir);
		$file=$dir."/".$key.".mp3";
		
		if(file_exists($file)){
			if(isset($_GET["rejson"])){
				$this->goAll("success",0,array(
					"audio"=>images_site($file)
				));
			}else{
				header("Content-type:audio/mp3;");
				echo file_get_contents($file);
			}
			
		}else{
			require ('module/tts/qcloud/TTSUtil.php');
			$cfg=MM("tts","tts_config")->getOpen();
			Config::$APPID=intval($cfg["appid"]);
			Config::$SECRET_ID=$cfg["secret_id"];
			CONFIG::$SECRET_KEY=$cfg["secret_key"];
			Config :: $TEXT = $word;
			Config :: $SESSION_ID = guid();
			$result = getVoice();
			$pcm_file = fopen($file, "w");
			fwrite($pcm_file, $result);
			if(isset($_GET["rejson"])){
				$this->goAll("success",0,array(
					"audio"=>images_site($file)
				));
			}else{
				header("Content-type:audio/mp3;");
				echo $result; 
			}
			
		}
		 
	}
}
?>