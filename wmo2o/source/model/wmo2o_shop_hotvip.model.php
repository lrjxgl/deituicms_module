<?php
class wmo2o_shop_hotvipModel extends model{
	public $table="mod_wmo2o_shop_hotvip";
	public function addUser($ops){
		$userid=$ops["userid"];
		$spid=$ops["spid"];
		$shopid=$ops["shopid"];
		$vip=$this->selectRow("spid=".$spid);
		$row=MM("wmo2o","wmo2o_shop_hotvip_user")->selectRow(array(
			"where"=>" userid=".$userid." AND spid=".$spid
		));
		if(empty($row)){
			if($vip["num"]>=$vip["has_num"]){
				$this->update(array(
					"has_num"=>$vip["has_num"]+1
				),"spid=".$spid);
			}else{
				$this->update(array(
					"has_num"=>$vip["has_num"]+1,
					"isfinish"=>1
				),"spid=".$spid);
			}
			MM("wmo2o","wmo2o_shop_hotvip_user")->insert(array(
				"userid"=>$userid,
				"spid"=>$spid,
				"shopid"=>$vip["shopid"],
				"createtime"=>date("Y-m-d H:i:s"),
				"vid"=>$vip["vid"]
			));
		}
		
	}
}