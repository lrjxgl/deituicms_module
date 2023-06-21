<?php
class mdish_shopModel extends model{
	public $table="mod_mdish_shop";
	public function __construct(){
		parent::__construct();
	}
	public function getListByIds($ids,$fields="*"){
		$ids=array_unique($ids);
		$res=$this->select(array(
			"where"=>" shopid in("._implode($ids).") ",
			"fields"=>$fields
		));
		$list=array();
		if($res){
			
			foreach($res as $rs){
				$list[$rs["shopid"]]=$rs;
			}
		}
		return $list;
	}
}