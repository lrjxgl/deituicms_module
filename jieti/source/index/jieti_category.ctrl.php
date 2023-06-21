<?php
class jieti_categoryControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
	}
	public function onIos(){
		$top=M("mod_jieti_category")->select(array(
			"where"=>"pid=0",
			"fields"=>"catid as id,title as value,pid as parentId"
		));
		$child=M("mod_jieti_category")->select(array(
			"where"=>"pid>0",
			"fields"=>"catid as id,title as value,pid as parentId"
		));
		$this->smarty->goAssign(array(
			"top"=>$top,
			"child"=>$child
		));
	}
}