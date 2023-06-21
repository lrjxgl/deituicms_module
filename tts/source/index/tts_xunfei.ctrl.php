<?php
use IFlytek\Xfyun\Speech\TtsClient;
class tts_xunfeiControl extends skymvc{
	public function onDefault(){
		
	}
	public function onApi(){
		include ROOT_PATH.'vendor/autoload.php';
		
		$appId = '2f47c0ef';// 此行代码需要更换为用户自己的appid
		$apiKey = 'Y2MxYzUzZDc1YThkZDUxNmZjZDkyZGFj';// 此行代码需要更换为用户自己的apikey
		$apiSecret = 'af1e6fdd87ace24cdc25653c41b5e76b';// 此行代码需要更换为用户自己的apiSecret
		
		$client = new TtsClient($appId, $apiKey, $apiSecret);
		file_put_contents('attach/tts.mp3', $client->request('欢迎使用科大讯飞语音能力，让我们一起用人工智能改变世界')->getBody()->getContents());
		return true;
	}
}
