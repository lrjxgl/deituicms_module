<?php
class shanxin_bangModel extends model{
	public $table="mod_shanxin_bang";
	public function __construct(){
		parent::__construct();
	}
	public function Add($ops){
		$this->insert($ops);
	}
}