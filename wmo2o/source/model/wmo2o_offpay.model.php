<?php
class wmo2o_offpayModel extends model{
	public $table="mod_wmo2o_offpay";
	
	public function pay($orderid,$ops){
		$row=$this->selectRow("orderid=".$orderid);
		if($row["ispay"]==1){
			return false;
		}
		$this->update(array(
			"recharge_id"=>$ops["recharge_id"],
			"paytype"=>$ops["paytype"],
			"ispay"=>1
		),"orderid=".$orderid);
		//给商家充值
		$money=$row["money"]*0.95;
		MM("wmo2o","wmo2o_shop_money")->addMoney(array(
			"shopid"=>$row["shopid"],
			"income"=>$money,
			"balance"=>$money,
			"content"=>"用户使用优惠买单支付".$money."元"
		));
		//通知商家
		MM("wmo2o","wmo2o_shop_notice")->insert(array(
			"shopid"=>$row["shopid"],
			"content"=>"用户使用优惠买单支付".$money."元"
		));
	}
	
	public function sendNewOrder($orderid){
		
	}
}