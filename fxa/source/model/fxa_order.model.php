<?php
class fxa_orderModel extends model{
	
	public $table="mod_fxa_order";
	public function __construct(){
		parent::__construct();
	}
	public function statusList(){
		return array(
			0=>"待确认",
			1=>"待发货",
			2=>"待收货",
			3=>"已完成",
			4=>"已取消"
		);
	}
	public function Dselect($option,&$rscount=false){
		$list=$this->select($option,$rscount);
		if($list){
			foreach($list as $v){
				$uids[]=$v["userid"];
				$pids[]=$v["productid"];
			}
			$us=M("user")->getUserByIds($uids);
			$pros=MM("fxa","fxa_product")->getListByIds($pids);
			$statusList=$this->statusList();
			foreach($list as $k=>$v){
				$v["nickname"]=$us[$v["userid"]]["nickname"];
				$v["user_head"]=images_site($us[$v["userid"]]["user_head"]);
				$v["product"]=$pros[$v["productid"]];
				$v["status_name"]=$statusList[$v["status"]];
				$list[$k]=$v;
			}
		}
		return $list;		
	}
	
	public function paySuccess($orderid){
		$order=$this->selectRow("orderid=".$orderid);
		if($order["ispay"]==1){
			return false;
		}
		$this->update(array(
			"ispay"=>1
		),"orderid=".$orderid);
		
		/*
		if($order["invite_userid"]){
			MM("fxa","fxa_ushare")->add(array(
				"orderid"=>$orderid,
				"userid"=>$order["invite_userid"],
				"money"=>$order["fx_money"],
				"productid"=>$order["productid"],
				"shopid"=>$order["shopid"]
			));
		}
		*/
		return true; 
	}
}