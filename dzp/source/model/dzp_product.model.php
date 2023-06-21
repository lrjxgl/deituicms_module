<?php
class dzp_productModel extends model{
	public $table="mod_dzp_product";
	public function __construct(){
		parent::__construct();
	}
	
	public function ptypeList(){
		return array(
			"gold"=>"金币",
			"coupon"=>"优惠券",
			"product"=>"产品"
		);
	} 
 
	public function getListByIds($ids){
		if(empty($ids)) return false;
		$res=$this->select(array("where"=>"productid in("._implode($ids).")"));
		if($res){
			foreach($res as $rs){
				$rs['imgurl']=images_site($rs['imgurl']);
				$data[$rs['productid']]=$rs;
			}
			return $data;
		}
	}
}