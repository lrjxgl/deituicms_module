<?php
class lltuan_productModel extends model{
	public $table="mod_lltuan_product";
	public function getListByIds($ids,$fields="*"){
		$ids=array_unique($ids);
		if(empty($ids)){
			return [];
		}
		$res=$this->select(array(
			"where"=>" productid in("._implode($ids).")",
			"fields"=>$fields
		));
		$list=[];
		if(!empty($res)){
			foreach($res as $rs){
				$list[$rs["productid"]]=$rs;
			}
		}
		return $list;
	}
}