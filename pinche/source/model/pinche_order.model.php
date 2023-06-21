<?php
class pinche_orderModel extends model{
	public $table="mod_pinche_order";
	public function __construct(){
		parent::__construct();
	}
	public function statusList(){
		return array(
			0=>"待成团",
			1=>"已成团",
			2=>"已上车",
			3=>"已完成",
			4=>"已取消"
		);
	}
	public function Dselect($ops=array(),&$rscount=false){
		$res=$this->select($ops);
		if($res){
			$statusList=$this->statusList();
			foreach($res as $rs){
				$lids[]=$rs["lineid"];
			}
			$lines=MM("pinche","pinche_line")->getListByIds($lids);
			foreach($res as $k=>$v){
				$v["line_title"]=$lines[$v["lineid"]]["title"];
				$v["status_name"]=$statusList[$v["status"]];
				$list[$k]=$v;
			}
		}
		return $list;
	}
	 
	public function cancel($orderid,$order=false){
		if(!$order){
			$order=M("mod_pinche_order")->selectRow("orderid=".$orderid);
		}
		M("notice")->add(array(
			"content"=>"您的拼车订单被取消了",
			"userid"=>$order["userid"]
		));
		//给用户退款
		if($order["ispay"]){
			//资金原路退回
			if($order["recharge_id"]){
				M("refund_apply")->add(
					$order["recharge_id"],
					"拼车订单取消，资金原路返回"
				);
			}else{
				M("user")->addMoney(array(
					"userid"=>$order["userid"],
					"money"=>$order["money"],
					"content"=>"拼车订单取消，资金原路返回"
				));
			}
			
		}
		
	} 
	
}