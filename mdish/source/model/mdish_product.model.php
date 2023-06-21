<?php
class mdish_productModel extends model{
	public $table="mod_mdish_product";
	public function __construct(){
		parent::__construct();
	}
	public function getListByIds($ids,$fields="*"){
		$ids=array_unique($ids);
		$res=$this->select(array(
			"where"=>" productid in("._implode($ids).") ",
			"fields"=>$fields
		));
		$list=array();
		if($res){
			
			foreach($res as $rs){
				$rs["imgurl"]=images_site($rs["imgurl"]);
				$list[$rs["productid"]]=$rs;
			}
		}
		return $list;
	}
}