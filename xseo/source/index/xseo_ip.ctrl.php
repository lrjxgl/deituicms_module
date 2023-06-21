<?php
class xseo_ipControl extends skymvc{
	public function onDefault(){
		
	}
	
	public function onPush(){
		$queue=new queue("xseo_amazon_ip");
		$queue->lpush("127.0.0.1");
		$queue->lpush("127.0.0.2");
		$queue->lpush("127.0.0.3");
		$list=$queue->getList();
		print_r($list);
	}
	public function onPop(){
		$queue=new queue("xseo_amazon_ip");
		$list=$queue->getList();
		print_r($list);
		$word=$queue->rpop();
		$list=$queue->getList();
		 
		echo $word;
	}
	
}