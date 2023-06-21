<?php
class b2b_order_codeControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$this->smarty->display("b2b_order_code/index.html");
	}
	
	public function onCheck(){
		$_GET["ajax"]=1;
		$ordercode=get("ordercode","h");
		$row=M("mod_b2b_order_code")->selectRow("ordercode='".$ordercode."'");
		if(empty($row)){
			$this->goAll("验证码错误",1);
		}
		if($row["shopid"]!=SHOPID){
			$this->goAll("验证码错误",1);
		}
		if($row["isuse"]){
			$this->goAll("当前订单已验证过了",1);
		}
		$orderid=$row["orderid"];
		$order=MM("b2b","b2b_order")->selectRow("orderid=".$orderid);
		$orderdata=MM("b2b","b2b_order_data")->get($orderid);
		$order["status_name"]=MM("b2b","b2b_order")->getStatus($order);
		$order["timeago"]=timeago(strtotime($order["createtime"]));
		$this->smarty->goAssign(array(
			"order"=>$order,
			"addr"=>$orderdata["address"],
			"prolist"=>$orderdata["prolist"]
		));
	}
	
	public function onFinish(){
		$ordercode=get("ordercode","h");
		$row=M("mod_b2b_order_code")->selectRow("ordercode='".$ordercode."'");
		if(empty($row)){
			$this->goAll("验证码错误",1);
		}
		if($row["shopid"]!=SHOPID){
			$this->goAll("验证码错误",1);
		}
		if($row["isuse"]){
			$this->goAll("当前订单已验证",1,$row["orderid"]);
		}
		$order=MM("b2b","b2b_order")->selectRow("orderid=".$row["orderid"]);
		
		if($order["ispay"]==0){
			$this->goAll("订单还未支付",1);
		}
		if($order["status"]>=3){
			$this->goAll("订单已处理",1);
		}
		MM("b2b","b2b_order")->begin();
		M("mod_b2b_order_code")->update(array(
			"isuse"=>1			
		),"orderid=".$row["orderid"]);
		$res=MM("b2b","b2b_order")->finish($row["orderid"]);
		MM("b2b","b2b_order")->commit();
		if(!$res["error"]){
			$this->goAll("订单验证成功",0,$row["orderid"]);
		}else{
			echo json_encode($res);
		}
	}
	
}