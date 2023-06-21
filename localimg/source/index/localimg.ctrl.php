<?php
class localimgControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		include_once(ROOT_PATH."api/ossapi/ossapi.php");
	}
	public function upload_oss($files){
		if(!UPLOAD_OSS) return false;
		if(empty($files)) return false;
		$arr=array("",".100x100.jpg",".small.jpg",".middle.jpg");
		if(file_exists(ROOT_PATH.$files.$a)){
			$to=str_replace("//","/",$files);
			$from=ROOT_PATH.$files.$a;
			$response = oss_upload_file(array("bucket"=>OSS_BUCKET,"to"=>$to,"from"=>$from));
			if(defined("UPLOAD_DEL") && UPLOAD_DEL){
				@unlink($from);
			}
		}
	} 
	public function onDefault(){
		$this->smarty->display("index.html");
	}
	
	public function onView(){
		$content=stripslashes( $_POST['content']);
		$content=$this->remote_img("attach/wximg/",$content);
		$this->smarty->goassign(array(
			"content"=>$content
		));
		$this->smarty->display("view.html");
	}
	public function remote_img($dir="",$content=''){
		preg_match_all("/<img.*(data-src=[\'\"]+.*[\'\"])[^>]*>/iUs",$content,$reps);
		$rep=$reps[1];
		if(!empty($rep)){
			foreach($rep as $rs){
				$content=str_replace($rs,"",$content);
			}
		}
		preg_match_all("/<img.* src=[\'\"]+(.*)[\'\"][^>]*>/iUs",$content,$arr);
		$pics=$arr[1];
	 
		if(empty($pics)) return $content;
		$dir=$dir?$dir:"attach/content/".date("Y/m/");
		umkdir($dir);
		foreach($pics as $k=>$pic)
		{
			$file=$dir.md5(time().$pic).".jpg";
			file_put_contents($file,curl_get_contents($pic));
			//$im=imagecreatefromstring(curl_get_contents($pic));
			//imagejpeg($im,$file);
			if(UPLOAD_OSS){
				$content=str_replace($pic,images_site($file),$content);
				$this->upload_oss($file);
			}else{
				$content=str_replace($pic,"/".$file,$content);
			}
		}
		return $content;
	}
	
}
?>