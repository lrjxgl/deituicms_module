<?php
class sjsj_configModel extends model{
	public $table="mod_sjsj_config";
	public function get(){
		$row=$this->selectRow(" 1 ");
		if(empty($row)){
			$this->insert(array(
				"id"=>1
			));
			$row=$this->selectRow(" 1 ");
		}
		return $row;
	}
}