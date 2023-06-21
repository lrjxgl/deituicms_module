<?php
class exue_shop_studentModel extends model{
	public $table="mod_exue_shop_student";
	public function __construct(){
		parent::__construct();
	}
	public function Dselect($option=array(),&$rscount=false){
		$res=parent::select($option,$rscount);
		if($res){
			$stids=array();
			foreach($res as $k=>$rs){
				$stids[]=$rs["stid"];
			}
			 
			$list=MM("exue","exue_student")->getListByIds($stids);
			 
			return $list;
		}
		return false;
	}
	 
	
}