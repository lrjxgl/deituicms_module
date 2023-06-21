<?php
class xseo_keywordControl extends skymvc{
	
	public function onDefault(){
		session_write_close();
		set_time_limit(0);
		$st=time();
		$redis=new redis();
		$redis->connect('127.0.0.1', 6379);
		$redis->del("xseo_amazon_list");
		for($i=0;$i<1000000;$i++){
			$redis->lPush("xseo_amazon_list","iphone ".$i);
			if($i%100000==0){
				echo str_repeat("       ",1000);
				echo "正在插入".$i."条数据<br/>";
				ob_flush();
				flush();
			}
			
			
		}
		print_r($redis->lLen("xseo_amazon_list"));
		$end=time();
		$time=$end-$st;
		echo "一共使用".$time."秒";
	}
	
	public function onTest(){
		$redis=new redis();
		$redis->connect('127.0.0.1', 6379);
		
		echo $redis->lLen("xseo_amazon_list")."<br/>";
		echo $redis->rPop("xseo_amazon_list")."<br/>";
	}
	
	public function onPush(){
		$queue=new queue("xseo_amazon_keyword");
		$queue->lpush("iphone");
		$queue->lpush("iphone 12");
		$queue->lpush("iphone 13");
		$list=$queue->getList();
		print_r($list);
	}
	public function onPop(){
		$queue=new queue("xseo_amazon_keyword");
		$list=$queue->getList();
		print_r($list);
		$word=$queue->rpop();
		$list=$queue->getList();
		 
		echo $word;
	}
	
}