<?php
class aichat_open_xunfeiModel extends model{
	public $table="mod_aichat_open_xunfei";
	public function get(){
		$row=$this->selectRow("1");
		if(empty($row)){
			$this->insert(array(
				"id"=>1
			));
			$row=$this->selectRow(" 1 ");
		}
		return $row;
	}
	 
}