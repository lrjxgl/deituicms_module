<?php
class csc_sender_orderModel extends model{
	public $table="mod_csc_sender_order";
	public function __construct(){
		parent::__construct();
	}
 
	
	public function statusList(){
		return array(
			0=>"待接单",
			1=>"待配送",
			2=>"待收货",
			3=>"已完成"
		);
	}
	
	public function Dselect($option,&$rscount=false){
		$data=$this->select($option,$rscount);
		if($data){
			$ptids=array();
			$orderids=array();
			foreach($data as $v){
				if($v["tablename"]=='paotui'){
					$ptids[]=$v["orderid"];
				}else{
					$orderids[]=$v["orderid"];
				}
				
			}
			
			$orders=MM("csc","csc_order")->getListByIds($orderids);
			$pts=MM("csc","csc_paotui")->getListByIds($ptids);
			$statusList=MM("csc","csc_sender_order")->statusList(); 
			foreach($data as $k=>$v){
				if($v["tablename"]=='paotui'){
					$v["paotui"]=$pts[$v["orderid"]];
					$v["status_name"]=$statusList[$v["status"]];
					$data[$k]=$v;
				}else{
					$o=$orders[$v["orderid"]];
					$o["ptorderid"]=$v["ptorderid"];
					$o["status_name"]=$statusList[$v["status"]];
					$data[$k]=$o;
				}
				
				
			}
		}
		return $data;
	}
	
	public function get($ptorderid){
		$data=$this->selectRow("ptorderid=".$ptorderid);
		$statusList=$this->statusList();
		$data["status_name"]=$statusList[$data["status"]];
		$data["timeago"]=timeago($data["stime"]);
		if($data["tablename"]=='paotui'){
			$paotui=M("mod_csc_paotui")->selectRow("id=".$data["orderid"]);
			 
			return array(
				"data"=>$data,
				"paotui"=>$paotui,
				"addr"=>array(
					"truename"=>$paotui["truename"],
					"address"=>$paotui["address"],
					"telephone"=>$paotui["telephone"]
				)
			);
		}else{
			
			
			$orderid=$data["orderid"];
			$order=MM("csc","csc_order")->selectRow("orderid=".$orderid);
			$orderdata=MM("csc","csc_order_data")->get($orderid);
			 
			$order["status_name"]=MM("csc","csc_order")->getStatus($order);
			
			return array(
				"data"=>$data,
				"order"=>$order,
				"addr"=>$orderdata["address"],
				"prolist"=>$orderdata["prolist"]
			);
		}
		
	}
	
}