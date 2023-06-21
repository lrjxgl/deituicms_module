<?php
	class ttsControl extends skymvc{
		public function __construct(){
			parent::__construct();
		}
		public function onTest(){
			$this->smarty->display("tts/test.html");
		}
		public function onDefault(){
			$key="bd_openapi_oauth"; 
			if(!$token=cache()->get($key)){
				$tk=curl_get_contents("https://openapi.baidu.com/oauth/2.0/token?grant_type=client_credentials&client_id=ACwBtcMwG82Vgfrj6jWzFBa8IIrSWvML&client_secret=LDya3SU2eyUFPDn4DOQj6VZBscABtM2O&");
				$tk=json_decode($tk,true); 
				
				$token=$tk['access_token'];
				cache()->set($key,$token,3600*24*7);
			}
			$text=strip_tags(get_post('text'));
			if(empty($ext)){
				header("Content-type:audio/mp3;");
				exit;
			}
			$url="http://tsn.baidu.com/text2audio?";
			$per=3;
			if(isset($_GET["person"])){
				$per=get("person","i");
			}
			$spd=5;
			if(isset($_GET["speed"])){
				$spd=get("speed","i");
			}
			$data=array(
				"tex"=>$text, 
				"lan"=>"zh",
				"tok"=>$token,
				"ctp"=>1,
				"cuid"=>md5(time().ip()),
				"spd"=>5,
				"pit"=>5,
				"vol"=>5,
				"per"=>$per//声音类型
			);
			foreach($data as $k=>$v){
				$data[$k]=urlencode($v);
			}
			$url.=http_build_query($data);
			header("Content-type:audio/mp3;");
			echo $mp3=file_get_contents($url);
		}
		
		public function onApi(){
			$key="bd_openapi_oauth"; 
			if(!$token=cache()->get($key)){
				$tk=curl_get_contents("https://openapi.baidu.com/oauth/2.0/token?grant_type=client_credentials&client_id=ACwBtcMwG82Vgfrj6jWzFBa8IIrSWvML&client_secret=LDya3SU2eyUFPDn4DOQj6VZBscABtM2O&");
				$tk=json_decode($tk,true); 
				
				$token=$tk['access_token'];
				cache()->set($key,$token,3600*24*7);
			}
			$text=strip_tags(get_post('text'));
			if(empty($text)){
				$this->goAll("请输入内容",1);
			}
			$url="http://tsn.baidu.com/text2audio?";
			$per=3;
			if(isset($_GET["person"])){
				$per=get("person","i");
			}
			$spd=5;
			if(isset($_GET["speed"])){
				$spd=get("speed","i");
			}
			$data=array(
				"tex"=>$text, 
				"lan"=>"zh",
				"tok"=>$token,
				"ctp"=>1,
				"cuid"=>md5(time().ip()),
				"spd"=>5,
				"pit"=>5,
				"vol"=>5,
				"per"=>$per//声音类型
			);
			foreach($data as $k=>$v){
				$data[$k]=urlencode($v);
			}
			$url.=http_build_query($data);
			$this->goAll("success",0,$url);
			//$dir="attach/tts/".date("Y/m/d");
			//umkdir($dir);
			//header("Content-type:audio/mp3;");
			//echo $mp3=file_get_contents($url);
		}
	}
?>