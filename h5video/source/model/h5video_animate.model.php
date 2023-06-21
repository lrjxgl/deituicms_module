<?php
class h5video_animateModel extends model{
	public $table="mod_h5video_animate";
	public function __construct(){
		parent::__construct();
	}
	
	public function inList(){
		$arr=array(
			"bounce"
		);
		return $arr;
	}
}
?>