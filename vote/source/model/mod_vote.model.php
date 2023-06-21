<?php
class mod_voteModel extends model{
	 
	public function __construct(){
		parent::__construct();
		 
		$this->table="mod_vote";
	}
	
	public function typelist(){
		return array(
			1=>"图片",
			2=>"文章",
			3=>"音频",
			4=>"视频"
		);
	}
}

?>