<?php
class im_msgModel extends model{
	public $table="mod_im_msg";
	public function parse($con){
		//解析img
		$con=preg_replace("/\[img=([^>]*)\]/iUs","<img src='\\1' class='w100' />",$con);
		//解析audio
		$con=preg_replace("/\[audio=([^>]*)\]/iUs","<audio src='\\1' controls ></audio>",$con);
		//解析video
		$con=preg_replace("/\[video=([^>]*)\]/iUs","<video class='w150' src='\\1' controls ></video>",$con);
		return $con;
	} 
	 
	 
}