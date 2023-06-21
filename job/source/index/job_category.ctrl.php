<?php
class job_categoryControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$tab=get("tab","h");
		$tab=$tab==''?'jianzhi':$tab;
		$table=$tab=='jianzhi'?'jianzhi':'quanzhi';
		$catList=MM("job","job_category")->children($table,0,1);
		 
		$this->smarty->goAssign(array(
			"catList"=>$catList,
			"tab"=>$tab
		));
		$this->smarty->display("job_category/index.html");
	}
	 
	
}