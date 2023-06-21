<?php
class jdo2o_groupModel extends model{
	public $table="mod_jdo2o_group";
	public function __construct(){
		parent::__construct();
	}
	public function getProductByKey($gkey){
		$group=$this->selectRow(array(
			"where"=>" gkey='".sql($gkey)."' ",
			 "fields"=>"gid,gnum"
		));
		if(!$group) return false;
		$data=M("mod_jdo2o_group_product")->select(array(
			"where"=>" gid=".$group["gid"],
			"limit"=>$group["gnum"],
			"order"=>" orderindex ASC,gpid DESC",
		));
		 
		if($data){
			foreach($data as $v){
				$proids[]=$v["productid"];
			}
			$pros=MM("jdo2o","jdo2o_product")->getListByIds($proids);
			foreach($data as $k=>$v){
				$p=$pros[$v["productid"]];
				$p["orderindex"]=$v["orderindex"];
				$p["productid"]=$v["productid"];
				$data[$k]=$p;
			}
		}
		return $data;
	}
}