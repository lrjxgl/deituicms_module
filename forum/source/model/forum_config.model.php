<?php
class forum_configModel extends model{
	
	public $table="mod_forum_config";
	public function get(){
		$row=$this->selectRow("1");
		if(empty($row)){
			$this->insert(array(
				"id"=>1
			));
			$row=$this->selectRow("1");
		}
		$data=str2arr($row["content"]);
		return $data;
	}
	
}