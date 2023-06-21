<?php 
	define("imAppToken","fd175.");
	
	define("GROUP_MSG_SAVE","redis");
	//分表
	define("MSG_TABLE_NUM",0);
	function ImRedis(){
		$redis=new Redis();
		$redis->connect("127.0.0.1","6379");
		return $redis;
	}
	function ImMongoDb(){
		
	}
?>