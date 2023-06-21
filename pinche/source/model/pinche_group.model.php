<?php
class pinche_groupModel extends model{
	public $table="mod_pinche_group";
	public $maxnum=4;
	public function __construct(){
		parent::__construct();
	}
	
	public function statusList(){
		return array(
			0=>"未成团",
			1=>"已接单",
			2=>"处理中",
			3=>"已完成",
			4=>"已取消"
		);
	}
	
	public function Dselect($ops,&$rscount=false){
		$res=$this->select($ops,$rscount);
		if(!empty($res)){
			$lineids=[];
			$statusList=$this->statusList();
			foreach($res as $rs){
				$lineids[]=$rs["lineid"];
			}
			$lines=MM("pinche","pinche_line")->getListByIds($lineids);
			foreach($res as $k=>$v){
				$v["line"]=$lines[$v["lineid"]];
				$v["wait_etime_fmt"]=date("H:i");
				$v["status_name"]=$statusList[$v["status"]];
				$res[$k]=$v;
			}
			return $res;
		}
	}
	
	/**
	 * @param {Object} $orderid
	 * 拼车成团逻辑
		1.包车 直接成团
		2.拼车 人数够 成团
		3.拼车 人数不够 加入团
		4.拼车 人数大于组团人数 新开团
	 */
	public function joinGroup($orderid,$retype=1){
		$order=MM("pinche","pinche_order")->selectRow("orderid=".$orderid);
		if(empty($order) || ($order["ispay"]==0 && $retype==1 ) || $order["status"]!=0){
			
			return false;
		}
		
		$line=MM("pinche","pinche_line")->selectRow("lineid=".$order["lineid"]);
		$driver=MM("pinche","pinche_driver")->getFree($order["lineid"]);
		
		 
		if(empty($driver)){
			$driverid=0; 
		}else{
			$driverid=$driver["driverid"]; 
		}
		//包车 直接开团
		if($order["usernum"]==$this->maxnum){
			if($driverid==0){
				$status=0;
			}else{
				$status=1;
			}
			$gid=$this->insert(array(
				"lineid"=>$order["lineid"],
				"dateline"=>time(),
				"usernum"=>$order["usernum"],
				"freenum"=>0,
				"status"=>$status,
				"driverid"=>$driverid,
				"money"=>$order["money"],
				"ptime"=>1,
				"wait_etime"=>time()+$line["wait_time"]*60
			));
			MM("pinche","pinche_order")->update(array(
				"driverid"=>$driverid,
				"gid"=>$gid,
				"status"=>1
			),"orderid=".$orderid);
			if($driverid){
				MM("pinche","pinche_driver_line")->update(array(
					"status"=>2
					
				),"driverid=".$driverid." AND lineid=".$order["lineid"]);
			}
			
			return true;
		}
		//普通拼车
		$group=$this->selectRow(array(
			"where"=>"lineid=".$order["lineid"]." AND status=0 AND freenum>=".$order["usernum"],
			"order"=>"gid ASC"
		));
		
		if(empty($group)){
			$gid=$this->insert(array(
				"lineid"=>$order["lineid"],
				"dateline"=>time(),
				"wait_etime"=>time()+$line["wait_time"]*60,
				"ptime"=>1,
			));
			$group=$this->selectRow("gid=".$gid);
		}
		//拼车成团
		if($order["usernum"]==$group["freenum"]){
			$money=MM("pinche","pinche_order")->selectOne(array(
				"where"=>"gid=".$group["gid"]." or orderid=".$orderid,
				"fields"=>" sum(money) as mmoney "
			));
			if($driverid==0){
				$status=0;
			}else{
				$status=1;
				MM("pinche","pinche_driver_line")->update(array(
					"status"=>2
				),"driverid=".$driverid." AND lineid=".$order["lineid"]);
			}
			$this->update(array(
				"usernum"=>$group["usernum"]+$order["usernum"],
				"freenum"=>0,
				"money"=>$money,
				"driverid"=>$driverid,
				"status"=>$status,
				"ptime"=>time()-$group["dateline"]
			),"gid=".$group["gid"]);
			MM("pinche","pinche_order")->update(array(
				"driverid"=>$driverid,
				"gid"=>$group["gid"],
				"status"=>1,
				"wait_etime"=>$group["wait_etime"]
			),"gid=".$group["gid"]." or orderid=".$orderid);
		}else{
			
			MM("pinche","pinche_order")->update(array(
				"gid"=>$group["gid"],
				"status"=>1,
				"wait_etime"=>$group["wait_etime"]
			),"orderid=".$orderid);
			$money=MM("pinche","pinche_order")->selectOne(array(
				"where"=>"gid=".$group["gid"],
				"fields"=>" sum(money) as mmoney "
			));
			$this->update(array(
				"money"=>$money,
				"usernum"=>$group["usernum"]+$order["usernum"],
				"freenum"=>$this->maxnum-$order["usernum"]-$group["usernum"],			
				"ptime"=>time()-$group["dateline"]
			),"gid=".$group["gid"]);
		}
		
		
	}
	
	public function cancel($gid,$group=false){
		
	}
	
	public function finish($gid,$group=false){
		if(!$group){
			$group=MM("pinche","pinche_group")->selectRow("gid=".$gid);
		}
		if(empty($group)) return false;
		$config=M("mod_pinche_config")->selectRow("1");
		$money= $group["money"]*(100-$config["site_per_money"])*0.01;
		MM("pinche","pinche_driver_account")->addMoney(array(
			"driverid"=>$group["driverid"],
			"money"=>$money,
			"content"=>"拼车订单完成，获得".$money."元"
		));
	}
	
}