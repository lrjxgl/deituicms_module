<?php
class test_ossControl extends skymvc{
	public function onDefault(){
		$url=get("url","h");
		$url=str_replace("http://fd175.skymvc.com/","",$url);
		$url=str_replace(".100x100.jpg","",$url);
		if(!empty($url)){
			$url2="https://fd175.oss-cn-hangzhou.aliyuncs.com/".$url;
			file_put_contents(ROOT_PATH.$url,file_get_contents($url2));
			file_put_contents(ROOT_PATH.$url.".100x100.jpg",file_get_contents($url2.".100x100.jpg"));
			file_put_contents(ROOT_PATH.$url.".small.jpg",file_get_contents($url2.".small.jpg"));
			file_put_contents(ROOT_PATH.$url.".middle.jpg",file_get_contents($url2.".middle.jpg"));
			echo "保存成功";
		}else{
			echo "请输入地址";
		}
		
	}
}