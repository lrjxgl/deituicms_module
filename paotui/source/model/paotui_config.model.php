<?php
class paotui_configModel extends model{
	public $table="mod_paotui_config";
	public function get(){
		$row=$this->selectRow("1");
		if(!$row){
			$this->insert(array(
				"id"=>1
			));
			$row=$this->selectRow("1");
		}
		return $row;
	}
}