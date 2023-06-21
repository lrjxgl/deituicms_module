<?php
class flk_orderModel extends model{
	public $table="mod_flk_order";
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
			$order=MM("flk","flk_order")->selectRow("orderid=".$orderid);
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
		
		$shop=M("mod_flk_shop")->selectRow(array(
			"where"=>"shopid=".$order["shopid"],
			"fields"=>"shopid,flk_discount"
		));
		MM("flk","flk_order")->update(array(
			"status"=>3,
			"isreceived"=>1
		),"orderid=".$orderid);
		//分配商家收益
		MM("flk","flk_shop_money")->addMoney(array(
			"shopid"=>$order["shopid"],
			"income"=>$order["shop_money"],
			"balance"=>$order["shop_money"],
			"content"=>"订单完成获得了".$order["shop_money"]."元收入，"
		));
		//返给消费者
		
		 
		if($order["ordertype"]=="one"){
			//秒杀订单直接返
			/*MM("flk","flk_queue")->one_backMoney(array(
				"shopid"=>$order["shopid"],
				"money"=>$bmoney,
				"userid"=>$buserid,
				"ordertype"=>$order["ordertype"],
				"productid"=>$order["productid"]
			));
			*/
		}else{
			if($order["flkid"]){
				M("mod_flk_queue")->update(array(
					"ischeck"=>1,
					"dateline"=>time()
				),"orderid=".$orderid);
			}
			$bmoney=($order["money"]-$order["flk_money"])*$shop["flk_discount"]/100;
			//如果没用返利券 则减半
			if($order["isflk"]==0){
				$bmoney=$bmoney/2;
			}
			$buserid=0;
			if($order["pin_orderid"]){
				$queque=MM("flk","flk_queue")->selectRow("orderid=".$order["pin_orderid"]);
			 
				if($queque && $queque["ischeck"] && !$queque["isfinish"]){
					$buserid=$queque["userid"];
				}
			}
			MM("flk","flk_queue")->backMoney($order["shopid"],$bmoney,$buserid,$order["ordertype"]);
		} 
		return array(
			"error"=>0,
			"message"=>"该订单处理完成"
		);
	}
	
	public function pay($ops,$w){
		$order=$this->selectRow($w);
		$this->update(array(
			"ispay"=>$ops["ispay"],
			"recharge_id"=>$ops["recharge_id"],
			"paytype"=>$ops["paytype"]
		),$w);
		//如果是秒杀直接返给用户
		$orderid=$order["orderid"];
		if($order["ordertype"]=="one"){
			//返给消费者
			if($order["flkid"]){
				M("mod_flk_queue")->update(array(
					"ischeck"=>1,
					"dateline"=>time()
				),"orderid=".$orderid);
			}
			$shop=M("mod_flk_shop")->selectRow(array(
				"where"=>"shopid=".$order["shopid"],
				"fields"=>"shopid,flk_discount"
			));
			//返给用户的钱
			$product=M("mod_flk_product")->selectRow(array(
				"where"=>"id=".$order["productid"],
				"fields"=>"one_discount,one_flk_discount,id"
			));
			
			$bmoney=($order["money"]-$order["flk_money"])*$product["one_flk_discount"]/100;
			//如果没用返利券 则减半
			if($order["isflk"]==0){
				$bmoney=$bmoney/2;
			}
			$buserid=0;
			if($order["pin_orderid"]){
				$queque=MM("flk","flk_queue")->selectRow("orderid=".$order["pin_orderid"]);
			 
				if($queque && $queque["ischeck"] && !$queque["isfinish"]){
					$buserid=$queque["userid"];
				}
			}
			MM("flk","flk_queue")->one_backMoney(array(
				"shopid"=>$order["shopid"],
				"money"=>$bmoney,
				"userid"=>$buserid,
				"pin_orderid"=>$order["pin_orderid"],
				"ordertype"=>$order["ordertype"],
				"productid"=>$order["productid"]
			));
		}
		
	}
}