<?php
class household_orderModel extends model{
	public $table="mod_household_order";
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
				if($data["ispin"] && $data["pin_success"]==0){
					$data['status_name']="待成团";
				}else{
					$data['status_name']="待接单";
				}
				
			}
			
		}
		if($data['status']==1){
			$data['status_name']="待办理";
		}
		if($data['status']==2 ){
			$data['status_name']="待验收";
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
	/**
	 * 处理拼团订单
	 */
	public function pinOrder($orderid){
		$order=$this->selectRow("orderid=".$orderid);
		if(empty($order) || !$order["ispin"]){
			return false;
		}
		if($order["pin_orderid"]){
			$order=$this->selectRow("orderid=".$order["pin_orderid"]);
		}
		$product=MM("household","household_product")->selectRow(array(
			"where"=>"id=".$order["productid"],
			"fields"=>"pt_min"
		));
		$child=$this->select(array(
			"where"=>"pin_orderid=".$order["orderid"]." AND ispay=1 AND status=0 "
		));
		$oids[]=$order["orderid"];
		if($child){
			foreach($child as $cc){
				$oids[]=$cc["orderid"];
			}
		}
		$pin_num=count($child)+1;
		$pin_success=0;
		if($pin_num>=$product["pt_min"]){
			$pin_success=1;
		}
		//更新
		$this->update(array(
			"pin_num"=>$pin_num,
			"pin_success"=>$pin_success
		)," orderid in ("._implode($oids).")");
	}
	
	public function Finish($order,$utype="sender"){
		$orderid=$order["orderid"];
		$cfg=M("mod_household_config")->selectRow("1");
		$urank=MM("household","household_sender_rank")->get($order["senderid"]);
		if($cfg["choudian_type"]==0){
			$sender_money=$order["money"]*(100-$cfg["choudian"])/100;
		}else{
			$udis=$urank["discount"]>0?$urank["discount"]*0.01:1;
			$sender_money=$order["money"]*(100-$cfg["choudian"]*$udis)*0.01*$udis;
		}
		
		MM("household","household_order")->update(array(
			"status"=>3,
			"isreceived"=>1,
			"sender_money"=>$sender_money,
			"updatetime"=>date("Y-m-d H:i:s")
		),"orderid=".$orderid);
		MM("household","household_sender")->addMoney(array(
			"senderid"=>$order["senderid"],
			"money"=>$sender_money
		));
		$uid=0;
		if($utype=='sender'){
			$uid=$order["senderid"];
		}else{
			$uid=$order["userid"];
		}
		MM("household","household_order_log")->insert(array(
			"createtime"=>date("Y-m-d H:i:s"),
			"content"=>"订单结束",
			"orderid"=>$order["orderid"],
			"utype"=>$utype,
			"userid"=>$uid
		));
		//进行邀请奖励
		$config=M("config")->selectRow("1");
		if($config["spread_on"]==1){
			$imoney=$order["money"]*$config["spread_discount"]/100;
			M("invite_account")->add(array(
					"per"=>1,
					"cuserid"=>$order["userid"],
					"money"=>$imoney,
					"content"=>"你邀请好友消费获得了".$imoney."元"
			));
		}
	}
}