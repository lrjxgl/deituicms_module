<?php
class jdo2o_orderModel extends model{
	public $table="mod_jdo2o_order";
	public function __construct(){
		parent::__construct();
	}
	public function Dselect(){
		
	}
	public function getStatus($data){
		if($data['status']==0 ){
			if($data['ispay']==0){
				$data['status_name']="待付款";
			}else{
				$data['status_name']="待发货";
			}
			
		}
		if($data['status']==1){
			$data['status_name']="待发货";
		}
		if($data['status']==2 ){
			$data['status_name']="待收货";
		}
	 
		if($data['status']==3 ){
			if($data['israty']==0){
				$data['status_name']="待评价";
			}else{
				$data['status_name']="已完成";
			}			
		}
		if($data['status']>3){
			$data['status_name']="已取消";
		}
		return $data['status_name'];
	}
	
	public function finish($orderid,$order=false){
		 
		if(!$order){
			$order=MM("jdo2o","jdo2o_order")->selectRow("orderid=".$orderid);
		}
		if(empty($order)){
			return array(
				"error"=>1,
				"message"=>"该订单无法处理"
			);
		}
		if($order["ispay"]==0){
			return array(
				"error"=>1,
				"message"=>"该订单还未支付"
			);
		}
		if($order["status"]>2){
			return array(
				"error"=>1,
				"message"=>"该订单已处理完成"
			);
		}
		if($order["isreceived"]==0){
			if(time()-strtotime($order["createtime"])<3600*24*7){
				return array(
					"error"=>1,
					"message"=>"该订单还未确认收货"
				);
			}
			
		}
		MM("jdo2o","jdo2o_order")->update(array(
			"status"=>3,
			"isreceived"=>1
		),"orderid=".$orderid);
		//分配商家收益
		$comm=MM("jdo2o","jdo2o_shop_commission")->get($order["shopid"]);		 
		$money=$order["money"]*(100-$comm["per"])/100;
		MM("jdo2o","jdo2o_shop_money")->addMoney(array(
			"shopid"=>$order["shopid"],
			"income"=>$money,
			"balance"=>$money,
			"content"=>"订单完成获得了".$money."元收入，"
		));
		return array(
			"error"=>0,
			"message"=>"该订单处理完成"
		);
	}
	
}