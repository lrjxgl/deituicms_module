<?php
class testControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onH5Pic(){
		set_time_limit(0);
		$res=M("attach")->select(array(
			"where"=>1
		)); 
	}
	
	public function onDefault(){
		set_time_limit(0);
		session_write_close();
		$res=M("attach")->select(array(
			"where"=>"1",
			"order"=>"id DESC"
		));
		$host="https://fd175.oss-cn-hangzhou.aliyuncs.com/";
		$ii=0;
		foreach($res as $k=>$v){
			 
			if(!file_exists(ROOT_PATH.$v["file_url"])){
				if($i>50){
					echo "<script>window.location.reload();</script>";
					exit;
				}
				$ii++;
				echo $v["id"]." ";
				$url=$host.$v["file_url"];
				umkdir(dirname($v["file_url"]));
				if($v["file_group"]=='img'){
					
					@file_put_contents(ROOT_PATH.$v["file_url"].".100x100.jpg",file_get_contents($url.".100x100.jpg"));
					@file_put_contents(ROOT_PATH.$v["file_url"].".small.jpg",file_get_contents($url.".small.jpg"));
					@file_put_contents(ROOT_PATH.$v["file_url"].".middle.jpg",file_get_contents($url.".middle.jpg"));
					@file_put_contents(ROOT_PATH.$v["file_url"],file_get_contents($url));
				}else{
					
					file_put_contents(ROOT_PATH.$v["file_url"],file_get_contents($url));
				}
			}else{
				//echo ROOT_PATH.$v["file_url"]."</br>";
			}
		}
		echo "图片下载成功";
	}

}

?>