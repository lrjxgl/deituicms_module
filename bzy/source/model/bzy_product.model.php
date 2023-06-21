<?php
class bzy_productModel extends model{
	public $table="mod_bzy_product";
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
	/**
	 * 获取奖品列表id
	 * @param {Object} $eventid
	 * @param {Object} $etype
	 */
	public function getEventIds($eventid,$etype){
		$res=$this->where("eventid=".$eventid." AND status=1 AND etype=".$etype)
			->field("eventid,productid,gailv,orderindex")
			->order("orderindex ASC")
			->all();
			
		if(empty($res)){
			return [];
		}
		$list=[];
		foreach($res as $rs){
			$arr=explode("-",$rs["gailv"]);
			foreach($arr as $a2){
				$list[]=$rs["productid"];
			}
		}
		
		return $list;
	}
	
}