<?php
class freeshop_orderModel extends model{
	public $table="mod_freeshop_order";
	public function __construct(){
		parent::__construct();
	}
	
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
	public function finish($orderid,$order=false){
		 
		if(!$order){
			$order=$this->selectRow("orderid=".$orderid);
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
		
		MM("freeshop","freeshop_order")->update(array(
			"status"=>3,
			"isreceived"=>1
		),"orderid=".$orderid);
		M("mod_freeshop_shop")->changenum("sold_num",1,"shopid=".$order["shopid"]);
		//分配商家收益
		$comm=MM("freeshop","freeshop_shop_commission")->get($order["shopid"]);		 
		$money=$order["money"]*(100-$comm["per"])/100;
		MM("freeshop","freeshop_shop_money")->addMoney(array(
			"shopid"=>$order["shopid"],
			"income"=>$money,
			"balance"=>$money,
			"content"=>"订单完成获得了".$money."元收入，"
		));
		//处理邀请
		MM("freeshop","freeshop_order")->doInvite($order);
		return array(
			"error"=>0,
			"message"=>"该订单处理完成"
		);
	}
	public function doInvite($order){
		//处理邀请用户赏金
		
		$product=M("mod_freeshop_product")->selectRow("productid=".$order["productid"]);
		$imoney=$product["invite_money"];
		
		if($order["invite_fsuserid"]){
			MM("freeshop","freeshop_invite_account")->add(array(
				"userid"=>$order["invite_fsuserid"],
				"to_userid"=>$order["userid"],
				"money"=>$imoney,
				"productid"=>$product["productid"],
				"content"=>"你邀请好友参与".$product["title"].",获得了{$imoney}元"
			));
			 
		}
	}
}
?>