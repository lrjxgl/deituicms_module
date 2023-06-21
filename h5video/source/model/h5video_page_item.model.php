<?php
class h5video_page_itemModel extends model{
	public $table="mod_h5video_page_item";
	public function __construct(){
		parent::__construct();
	}
	
	public function itypeList(){
		return array(
			"text"=>"文字",
			"img"=>"图片",
			"link"=>"链接",
			"plugin"=>"组件"
			
		);
	}
	
	public function parseCss($content){
		if(empty($content)){
			return false;
		}
		$content=str_replace(array("'",".",'"'),"",$content);
		return $content;
	}
	
}

?>