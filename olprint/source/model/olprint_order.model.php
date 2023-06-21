<?php
class olprint_orderModel extends model{
	public $table="mod_olprint_order";
	public function __construct(){
		parent::__construct();
		
	}
	public function statusList(){
		$statusList=array(
			0=>"待接单",
			1=>"待打印",
			2=>"待取货",
			3=>"已完成",
			4=>"已取消"
		);
		return $statusList;
	}
	public function getStatus($status){
		$statusList=$this->statusList();
		return $statusList[$status];
	}
	
	public function Dselect($ops,&$rscount=false){
		$res=$this->select($ops,$rscount);
		$statusList=$this->statusList();
		$ptypeList=MM("olprint","olprint_ptype")->ptypeList();
		if($res){
			$bookids=array();
			foreach($res as $v){
				if($v["bookid"]){
					$bookids[]=$v["bookid"];
				}
			}
			$books=MM("olprint","olprint_book")->getListByIds($bookids,"bookid,title");
			foreach($res as $k=>$rs){
				if($rs["ispay"]==0 && $rs["status"]==0){
					$rs["status_name"]="待支付";
				}else{
					$rs["status_name"]=$statusList[$rs["status"]];
				}
				$rs["sendtype_name"]=$rs["sendtype"]==0?'自取':"配送";
				$rs["ispay_name"]=$rs["ispay"]?"已支付":"待支付";
				$rs["ptype_name"]=$ptypeList[$rs["ptype"]]["title"];
				if($rs["bookid"]){
					$rs["book"]=$books[$rs["bookid"]];
				}
				$res[$k]=$rs;
			}
		}
		return $res;
	}
	
	public function finish($orderid,$order=false){
		if(!$order){
			$order=$this->selectRow("orderid=".$orderid);
		}
		 
		$this->update(array(
			"status"=>3		
		),"orderid=".$orderid);
		//给商家金额
		
		$money=$order["shop_money"];
		MM("olprint","olprint_shop_money")->addMoney(array(
			"income"=>$money,
			"balance"=>$money,
			"content"=>"打印订单完成收入".$money."元",
			"shopid"=>$order["shopid"]
		));
		if($order["bookid"]){
			$book=MM("olprint","olprint_book")->selectRow(array(
				"where"=>"bookid=".$order["bookid"],
				"fields"=>"bookid,userid"
			));
			MM("olprint","olprint_book")->changenum("print_num",1,"bookid=".$order["bookid"]);
			if($book["userid"]){
				M("user")->addMoney(array(
					"userid"=>$book["userid"],
					"money"=>$order["ubook_money"],
					"content"=>"用户打印了您的共享资料获得了".$order["ubook_money"]."元"
				));
			} 
			
		}
	 
		
	}
	
	
	public function cancel($orderid,$order=false){
		if(!$order){
			$order=$this->selectRow("orderid=".$orderid);
		}
		
		$this->update(array(
			"status"=>4		
		),"orderid=".$orderid);
		//退还用户
		
		 
		
	}
	
}