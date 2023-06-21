<?php
class tutor_configModel extends model{
	public $table="mod_tutor_config";
	public function get(){
		$row=$this->selectRow(" 1 ");
		if(empty($row)){
			$this->insert(array(
				 
				"per_money"=>10
			));
			$row=$this->selectRow(" 1 ");
		}
		return $row;
	}
}