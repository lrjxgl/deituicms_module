<?php
class xseo_amazon_productModel extends model{
	public $table="mod_xseo_amazon_product";
	public function getListByIds($ids,$fields="*"){
		$res=$this->select(array(
			"where"=>"productid in("._implode($ids).") ",
			"fields"=>$fields
		));
		$list=[];
		if(!empty($res)){
			foreach($res as $rs){
				$rs["url"]="https://www.amazon.com/dp/".$rs["asin"];
				$list[$rs["productid"]]=$rs;
			}
		}
		return $list;
	}
	
}