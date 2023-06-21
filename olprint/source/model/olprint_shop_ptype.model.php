<?php
class olprint_shop_ptypeModel extends model{
	public $table="mod_olprint_shop_ptype";
	public function __construct(){
		parent::__construct();
	}
	public function onlineList($shopid){
		$res=$this->select(array(
			"where"=>"shopid=".$shopid." AND status=1 "
		));
		$list=array();
		if($res){
			foreach($res as $k=>$v){
				$list[$v["ptype"]]=$v;
			}
		}
		return $list;
	}
	
}