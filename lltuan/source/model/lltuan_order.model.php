<?php
class lltuan_orderModel extends model{
	public $table="mod_lltuan_order";
	public function getListByIds($ids,$fields="*"){
		$ids=array_unique($ids);
		if(empty($ids)){
			return [];
		}
		$res=$this->select(array(
			"where"=>" orderid in("._implode($ids).")",
			"fields"=>$fields
		));
		$list=[];
		if(!empty($res)){
			foreach($res as $rs){
				$list[$rs["orderid"]]=$rs;
			}
		}
		return $list;
	}
}