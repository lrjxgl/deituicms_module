<?php
class job_jianliModel extends model{
	public $table="mod_job_jianli";
	public $xueliList=array(
		1=>"本科及以上",
		2=>"高中",
		3=>"初中",
		4=>"其他"
	);
	public function __construct(){
		parent::__construct();
	}
	
	
	
}

?>