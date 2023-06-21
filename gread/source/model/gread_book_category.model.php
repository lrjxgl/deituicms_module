<?php
class gread_book_categoryModel extends model{
	public $table="mod_gread_book_category";
	public function __construct(&$base=null){
		parent::__construct($base);
	}
	
	public function getList($status=2){
		if($status){
			$where=" status=".$status;
		}else{
			$where=" status<11 ";
		}
		$data=$this->select(array(
			"where"=>$where
		));
		 
		 
		if($data){
			foreach($data as $v){
				$sdata[$v['catid']]=$v;
			}
			return $sdata;
		}
		
	}
	
}
?>