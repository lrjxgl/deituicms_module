<?php
class test_queueControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$queue=new queue("zipImgList","file");
		$task=$queue->rpop();
		if(empty($task)){
			echo "处理完成";
			return false;
		}
		
		$dir=dirname($task["filename"]);
		umkdir($dir);
		$zip=new pclzip($task["filename"]);
		$files=[];
		foreach($task["files"] as $img){
			$files[]= $img;
			file_put_contents($img,file_get_contents(images_site($img)));
		}
		$zip->create(implode(",",$files),PCLZIP_OPT_REMOVE_ALL_PATH);
		foreach($files as $file){
			unlink($file);
		}
		$queue->rpush($task);
	}
	
}