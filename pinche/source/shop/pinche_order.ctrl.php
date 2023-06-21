<?php
class pinche_orderControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		 
	}
	public function onDefault(){
 
	}
	
	public function onCancel(){
		$orderid=get("orderid","i");
		$order=M("mod_pinche_order")->selectRow("orderid=".$orderid);
		if($order["driverid"]!=DRIVERID){
			$this->goAll("暂无权限",1);
		}
		if($order["status"]>2){
			$this->goAll("订单无法取消咯",1);
		}
		MM("pinche","pinche_order")->update(array(
			"status"=>4,
		),"orderid=".$orderid);
		M("mod_pinche_order_log")->insert(array(
			"orderid"=>$orderid,
			"role"=>"driver",
			"roleid"=>DRIVERID,
			"action"=>"cancel",
			"dateline"=>time(),
			"status"=>1,
			"content"=>"司机取消了订单"
		));
		MM("pinche","pinche_order")->cancel($orderid);
		$this->goAll("取消成功");
		
	}
	
	public function onSend(){
		$orderid=get("orderid","i");
		$order=M("mod_pinche_order")->selectRow("orderid=".$orderid);
		 
		if($order["driverid"]!=DRIVERID){
			$this->goAll("暂无权限",1);
		}
		if($order["status"]>1){
			$this->goAll("订单异常",1);
		}
		M("mod_pinche_order")->update(array(
			"status"=>2,
		),"orderid=".$orderid);
		M("mod_pinche_order_log")->insert(array(
			"orderid"=>$orderid,
			"role"=>"driver",
			"roleid"=>DRIVERID,
			"action"=>"send",
			"dateline"=>time(),
			"status"=>1,
			"content"=>"司机接乘客上车了"
		));
		$this->goAll("接客成功");
		
	}
	
	public function onFinish(){
		$orderid=get("orderid","i");
		$order=M("mod_pinche_order")->selectRow("orderid=".$orderid);
		 
		if($order["driverid"]!=DRIVERID){
			$this->goAll("暂无权限",1);
		}
		if($order["status"]!=2){
			$this->goAll("订单异常",1);
		}
		M("mod_pinche_order")->update(array(
			"status"=>3
		),"orderid=".$orderid);
		M("mod_pinche_order_log")->insert(array(
			"orderid"=>$orderid,
			"role"=>"driver",
			"roleid"=>DRIVERID,
			"action"=>"finish",
			"dateline"=>time(),
			"status"=>1,
			"content"=>"乘客到站下车了"
		));
		$this->goAll("接客成功");
		
	}
	
	
}