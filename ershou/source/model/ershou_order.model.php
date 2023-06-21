<?php
class ershou_orderModel extends model{
	public $table="mod_ershou_order";
	
	
	public function statuslist(){
		return array(
			0=>"待确认",
			1=>"待发货",
			2=>"待收货",
			3=>"已完成",
			4=>"已取消"
			
		);
	}
	public function getStatus($order){
		$name="";
		if($order['status']==0){
			if($order["ispay"]==0){
				return "待付款";
			}

			return "待处理";
		}
		if($order["status"]==1){
			return "待发货";
		}
		if($order["status"]==2){
			return "待收货";
		}
		if($order["status"]==3){
			if($order["israty"]==0){
				return "待评价";
			}
			return "已完成";
		}
		return "已取消";
	}
	
	public function paySuccess($ops,$orderid){
		$this->update(array(
			"ispay"=>1,
			"recharge_id"=>$ops["recharge_id"],
			"paytype"=>$ops["pay_type"]
		),"orderid=".$orderid);
	}
	
	public function finish($orderid){
		$order=$this->selectRow("orderid=".$orderid);
		$this->update(array(
			"status"=>3,
			"isreceived"=>1
		),"orderid=".$orderid);
		//结账
		M("mod_ershou_shop")->changenum("sold_num",1,"shopid=".$order["shopid"]);
		//分配商家收益
		$comm=MM("ershou","ershou_shop_commission")->get($order["shopid"]);		 
		$money=$order["money"]*(100-$comm["per"])/100;
		MM("ershou","ershou_shop_money")->addMoney(array(
			"shopid"=>$order["shopid"],
			"income"=>$money,
			"balance"=>$money,
			"content"=>"订单完成获得了".$money."元收入，"
		)); 
	}
	
}