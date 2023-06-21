<?php
class fsbuy_orderModel extends model{
	public $table="mod_fsbuy_order";
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
			if($order["pin_success"]==0){
				return "待成团";
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
	
	public function doInvite($order){
		//处理邀请用户赏金
		
		$fsbuy=M("mod_fsbuy")->selectRow("fsid=".$order["fsid"]);
		$imoney=$fsbuy["invite_money"];
		
		if($order["invite_fsuserid"]){
			M("user")->addMoney(array(
				"userid"=>$order["invite_fsuserid"],
				"money"=>$imoney,
				"content"=>"你邀请好友参与".$fsbuy["title"].",获得了{$imoney}元"
			));
			M("mod_fsbuy_invite_log")->insert(array(
				"userid"=>$order["invite_fsuserid"],
				"to_userid"=>$order["userid"],
				"money"=>$imoney,
				"fsid"=>$fsbuy["fsid"]
			));
		}
	}
	
	public function paySuccess($ops,$orderid){
		$pin_success=0;
		$order=$this->selectRow("orderid=".$orderid);
		
		$num=$this->selectOne(array(
			"where"=>" fsid=".$order["fsid"]." AND ispay=1 AND status<4 ",
			"fields"=>" count(*) as ct "
		));
		$fsbuy=M("mod_fsbuy")->selectRow(array(
			"where"=>" fsid=".$order["fsid"],
			"fields"=>"fsid,minnum"
		));
		if($num>=$fsbuy["minnum"]-1){
			$pin_success=1;
			$this->update(array(
				"pin_success"=>1
			)," ispay=1 AND fsid=".$order["fsid"]." AND pin_success=0 ");
		}
		$this->update(array(
			"ispay"=>1,
			"recharge_id"=>$ops["recharge_id"],
			"paytype"=>$ops["pay_type"],
			"pin_success"=>$pin_success
		),"orderid=".$orderid);
		
	}
}
?>