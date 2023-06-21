<?php
class aichat_vipModel extends model{
	public $table="mod_aichat_vip";
	public function buyToken($userid,$orderid){
		$aiuser=MM("aichat","aichat_user")->get($userid);
		$row=M("mod_aichat_vip_order")->selectRow("orderid=".$orderid);
		MM("aichat","aichat_user")->addVip(array(
			"userid"=>$userid,
			"num"=>$row["num"]
		));
	}
}