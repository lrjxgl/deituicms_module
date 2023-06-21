<?php 
class test_consulControl extends skymvc{
	public function onDefault(){
		
	}
	public function onReg(){
		$curl=new curl();
		$url="http://127.0.0.1:8500/v1/agent/service/register";
		$json='{
		  "ID": "fd175.skymvc.com",  
		  "Name": "fd175.skymvc.com",
		  "Tags": [
			"primary",
			"v1"
		  ],
		  "Address": "fd175.skymvc.com",
		  "Port": 80,
		  "EnableTagOverride": false,
		  "Check": {
			"DeregisterCriticalServiceAfter": "90m",
			"HTTP": "http://fd175.skymvc.com",
			"Interval": "10s"
		  }
		}';
		$res=$curl->put($url,$json);
		var_dump($res);
	}
	
	public function onGet(){
		$url="http://127.0.0.1:8500/v1/catalog/service/fd175.skymvc.com";
		$curl=new curl();
		$res=$curl->get($url);
		$arr=json_decode($res,true);
		print_r($arr[0]);
	}
	
	public function onSetK(){
		$url="http://127.0.0.1:8500/v1/kv/my-key";
		$curl=new curl();
		$json=json_encode("haha");
		$res=$curl->put($url,$json);
		$res=$curl->get($url);
		$arr=json_decode($res,true);
		 
		echo(base64_decode($arr[0]["Value"]));
	}
}