<?php
class h5video_styleControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
	}
	public function onAllCss(){
		$styleList=M("mod_h5video_style")->select();
		$css="";
		if($styleList){
			foreach($styleList as $v){
				$css.=$v["classData"];
			}
		}
		header("Content-type:text/css;");
		echo $css;
	}
	
}