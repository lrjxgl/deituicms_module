<?php
class test_imgControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$dir="attach/demo/category";
		$files=glob($dir."/*");
		$this->loadClass("image",false,false);
		$im=new image();
		 
		foreach($files as $file){
			
			if($file==$dir."/data.php" || !is_file($file)) continue;
			$filename=$dir."/done/".md5(str_replace(".png","",basename($file))).".png";
			file_put_contents($filename,file_get_contents($file));
			$im->makethumb($filename.".100x100.jpg",$file,160,160,1);
			$im->makethumb($filename.".small.jpg",$file,440);
			$im->makethumb($filename.".middle.jpg",$file,750);
		}
		echo "success";
	}
	
}
?>