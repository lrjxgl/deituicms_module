<?php
class taoke_groupModel extends model{
	public $table="mod_taoke_group";
	public function __construct(){
		parent::__construct();
	}
	public function getProductByKey($gkey,$pw=""){
		$group=$this->selectRow(array(
			"where"=>" gkey='".sql($gkey)."' ",
			 "fields"=>"gid,gnum"
		));
		if(!$group) return false;
		$data=M("mod_taoke_group_product")->select(array(
			"where"=>" gid=".$group["gid"],
			"limit"=>$group["gnum"],
			"order"=>" orderindex ASC,gpid DESC",
		));
		 
		if($data){
			foreach($data as $v){
				$proids[]=$v["productid"];
			}
			$pros=MM("taoke","taoke")->getListByIds($proids,$pw);
			foreach($data as $k=>$v){
				if(isset($pros[$v["productid"]])){
					$v=$pros[$v["productid"]];
					$data[$k]=$v;
				}else{
					unset($data[$k]);
				}
			}
		}
		return $data;
	}
}