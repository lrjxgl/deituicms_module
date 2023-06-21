<?php
class checkin_configModel extends model{
	public $table="mod_checkin_config";
	public function get(){
		$row=$this->row();
		if(empty($row)){
			$this->insert(array(
				"grade"=>1,
				"gold"=>1
			));
			$row=$this->row();
		}
		return $row;
	}
	
	public function moodList(){
		$arr=array(
			0=>"微笑",
			1=>"开心",
			2=>"悲伤", 
			3=>"愤怒",
		 
		);
		return $arr;
	}
}