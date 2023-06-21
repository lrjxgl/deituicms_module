<?php
class csc_orderModel extends model{
	public $table="mod_csc_order";
	public function __construct(){
		parent::__construct();
	}
	public function Dselect($option,$rscount){
		$data=MM("csc","csc_order")->select($option,$rscount);
		if($data){
			foreach($data as $v){
				$oids[]=$v["orderid"];
			}
			$ods=MM("csc","csc_order_data")->getListByOrderIds($oids);
			foreach($data as $k=>$v){
				$v['addr']=$ods[$v['orderid']]['address'];
				$v['prolist']=$ods[$v['orderid']]['prolist'];
				$v["status_name"]=$this->getStatus($v);
				$v["timeago"]=timeago(strtotime($v["createtime"]));
				$data[$k]=$v;
			}
		}
		return $data;
	}
	public function getListByIds($oids){
		$oids=array_unique($oids);
		if(empty($oids)){
			return false;
		}
		$data=MM("csc","csc_order")->select(array(
			"where"=>" orderid in("._implode($oids).") "
		));
		
		if($data){
			$list=array();
			foreach($data as $v){
				$oids[]=$v["orderid"];
			}
			$ods=MM("csc","csc_order_data")->getListByOrderIds($oids);
			foreach($data as $k=>$v){
				$v['addr']=$ods[$v['orderid']]['address'];
				$v['prolist']=$ods[$v['orderid']]['prolist'];
				$v["status_name"]=$this->getStatus($v);
				$v["timeago"]=timeago(strtotime($v["createtime"]));
				$list[$v["orderid"]]=$v;
			}
			 
			return $list;
		}
		return false;
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
			$order=MM("csc","csc_order")->selectRow("orderid=".$orderid);
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
		
		MM("csc","csc_order")->update(array(
			"status"=>3,
			"isreceived"=>1
		),"orderid=".$orderid);
		
		 
		//分配商家收益
		$comm=MM("csc","csc_shop_commission")->get($order["shopid"]);		 
		$money=$order["money"]*(100-$comm["per"])/100;
		//分配商家收益
		MM("csc","csc_shop_money")->addMoney(array(
			"shopid"=>$order["shopid"],
			"income"=>$money,
			"balance"=>$money,
			"content"=>"订单完成获得了".$money."元收入，"
		));
		//配送员相关
		$ptorder=MM("csc","csc_sender_order")->selectRow("orderid=".$orderid." AND senderid=".$order["senderid"]);
		if($ptorder){
			MM("csc","csc_sender_order")->update(array(
				"status"=>3
			),"ptorderid=".$ptorder["ptorderid"]);
			MM("csc","csc_sender_money")->addMoney(array(
				"senderid"=>$ptorder["senderid"],
				"income"=>$ptorder["money"],
				"balance"=>$ptorder["money"],
				"content"=>"配送订单".$order["orderno"]."完成,获得了".$ptorder["money"]."元"
			));
		}
		//供应商相关
		return array(
			"error"=>0,
			"message"=>"该订单处理完成"
		);
	}
	
}