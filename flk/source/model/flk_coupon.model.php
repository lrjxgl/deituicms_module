<?php
class flk_couponModel extends model{
	 
	public function __construct(){
		parent::__construct();
		 
		$this->table="mod_flk_coupon";
	}
	
	 
	
	public function getByIds($ids){
		if($ids){
			$data=$this->select(array("where"=>" id in("._implode($ids).")"));
			if($data){
				foreach($data as $v){
					$t_d[$v['id']]=$v;
				}
				return $t_d;
			}
		}
	}
	public function listByShop($userid,$shopid){
		$where=" status=0 AND shopid=".$shopid." AND userid=".$userid;
		$da=M("mod_flk_coupon_user")->select(array("where"=>$where));
		if($da){
			foreach($da as $v){
				$ids[]=$v['coupon_id'];
			}
			$coupons=$this->select(array("where"=>"  id in("._implode($ids).") "));
			return $coupons;
		}
	}
	/**
	 * 可用优惠券
	 */
	public function UseList($shopid,$userid,$money){
		//无需取券
		$where=" status=1 AND typeid=1 AND lower_money<".$money." AND end_time>".time()." AND shopid=".$shopid;
		$list=M("mod_flk_coupon")->select(array("where"=>$where));
		//已获取的券
		$where=" status=0 AND shopid=".$shopid."  AND userid=".$userid;
		$da=M("mod_flk_coupon_user")->select(array("where"=>$where));
		$coupons=array();
		if($da){
			foreach($da as $v){
				$ids[]=$v['coupon_id'];
			}
			$coupons=$this->select(array("where"=>"  id in("._implode($ids).") AND lower_money<".$money." "));
		}
		//合并
		$data=array();
		if($list){
			foreach($list as $v){
				$data[$v["id"]]=$v;
			}
		}
		if($coupons){
			foreach($coupons as $v){
				$data[$v["id"]]=$v;
			}
		}
		return $data;
	}
	public function UseList2($userid,$money){
		$where=" status=0  AND userid=".$userid;
		$da=M("mod_flk_coupon_user")->select(array("where"=>$where));
		if($da){
			foreach($da as $v){
				$ids[]=$v['coupon_id'];
			}
			$coupons=$this->select(array("where"=>"  id in("._implode($ids).") AND lower_money<".$money." "));
			return $coupons;
		}
	}
	
}

?>