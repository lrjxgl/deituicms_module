<?php
class qiye_configModel extends model{
	public $table="mod_qiye_config";
	public function get(){
		$row=$this->selectRow("1");
		if(empty($row)){
			$this->insert(array(
				"article_catid"=>0
			));
			$row=$this->selectRow("1");
		}
		return $row;
		
	}
}
?>