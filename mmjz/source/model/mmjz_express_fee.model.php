<?php
class mmjz_express_feeModel extends model{
	 
	public function __construct(){
		parent::__construct();
		 
		$this->table="mod_mmjz_express_fee";
	}
	
 
	
	public function getMoney($user_address_id=0,$weight=1,$shopid=0,$express_fee=0){
	 	if($weight==0) return 0;
		$user_address_id=intval($user_address_id);
		$weight=max(1,$weight);
		 
		$user_address=M("user_address")->selectRow("id=".$user_address_id);
	 	 
		if(!$user_address){
			return $express_fee+$weight-1;
		}
		 
		$where=" areaid=".$user_address['province_id'];
		if($shopid){
			$where.=" AND shopid=".$shopid;
		}
		 
		$r=M("shop_express_fee_city")->selectRow($where);
	 
		if($r){
			$ex=M("shop_express_fee")->selectRow("id=".$r['pid']);
			 
			if($weight>$ex['fweight']){
				 
				return ($ex['fmoney']+ceil($weight-$ex['fweight'])*$ex['amoney']);
			}else{
				return $ex['fmoney'];
			}
			//return ($ex['fmoney']+intval($weight-1)*$ex['amoney']);
			
		}else{
			return $express_fee;
		}
	}
	
	 
	
	 
}

?>