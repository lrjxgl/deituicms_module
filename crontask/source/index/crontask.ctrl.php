<?php
class crontaskControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$queue=new queue("crontask","file");
		$task=$queue->rpop();
		if(empty($task)){
			echo "处理完成";
			return false;
		}
		if(!empty($task["action"])){
			CC("crontask",$task["action"])->onDefault($task,$queue);
		}
		echo "success";
		//$queue->rpush($task);
	}
	
}