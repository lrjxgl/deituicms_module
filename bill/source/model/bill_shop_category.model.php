<?php
class bill_shop_categoryModel extends model{
	public $table="mod_bill_shop_category";
	public function __construct(){
		parent::__construct();
	}
	
	public function getListByIds($ids,$fields="*"){
		$ids=array_unique($ids);
		if(empty($ids)) return false;
		$res=$this->select(array(
			"where"=>" catid in("._implode($ids).") ",
			"fields"=>$fields
		));
		if($res){
			$list=array();
			foreach($res as $rs){
				$list[$rs["catid"]]=$rs;
			}
			return $list;
		}	
	}
	
}