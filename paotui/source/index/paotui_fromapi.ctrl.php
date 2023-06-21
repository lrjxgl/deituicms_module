<?php
class paotui_fromapiControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		
	}
	
	public function onPost(){
		$data=array(); 
		$tablename=post("tablename","h");
		$acToken=post("acToken","h");
		if(!cache()->get($acToken)){
			$this->goAll("权限出错",1);
		}
		$data["fromapi"]=$tablename;
		$orderid=post("orderid","i");
		$data["fromapi_params"]=$orderid;
		$data['money']=post("money","h");
		if($data['money']<1){
			$this->goAll("赏金必须大于1元",1);
		}
		$order=M("mod_".$tablename."_order")->selectRow("orderid=".$orderid);
		if(empty($order)){
			$this->goAll("订单不存在",1);
		}
		if(isset($order["ispay"]) && !$order["ispay"]==1){
			$this->goAll("订单未支付",1);
		}
		if($order["paotui_id"]){
			$this->goAll("已经发布跑腿任务了",1);
		}
		$shopid=$order["shopid"];
		$shop=M("mod_".$tablename."_shop")->selectRow("shopid=".$shopid);
		if(empty($shop)){
			$this->goAll("商家不存在",1);
		}
		//处理支付
		$account=MM($tablename,$tablename."_shop_money")->get($shopid);
		if($account["balance"]<$data["money"]){
			$this->goAll("账户余额不足",1);
		}
		MM($tablename,$tablename."_shop_money")->addMoney(array(
			"shopid"=>$shopid,
			"balance"=>-$data["money"],
			"content"=>"发布跑腿任务扣除".$data["money"]."元"
		));
		
		$orderData=M("mod_".$tablename."_order_data")->selectRow("orderid=".$orderid);
		$odata=str2arr($orderData["content"]);
		 
		$content="日单号:".$order["daySn"]."<br/>";
		$content.="订单商品：<br/>";
		foreach($odata["prolist"] as $v){
			$content.=$v["title"]." ".$v["ks_title"]." ".$v["amount"]."份,<br/>";
		}
		$data["content"]=$content;
		//取货地址
		$fromAddr=array(
			"truename"=>$shop["shopname"],
			"telephone"=>$shop["telephone"],
			"address"=>$shop["address"]
		);
		$data["fromaddr"]=addslashes(json_encode($fromAddr)) ;
		//送货地址
		
		$addr=M("mod_".$tablename."_order_address")->selectRow("orderid=".$orderid);
		$toAddr=array(
			"truename"=>$addr["truename"],
			"telephone"=>$addr["telephone"],
			"address"=>$addr["address"]
		);
		$data["toaddr"]=addslashes(json_encode($toAddr)) ;
		//
		$data['createtime']=date("Y-m-d H:i:s");
		
		
	
		$data["ispay"]=1;	
		$data['status']=0;
		$id=M("mod_paotui")->insert($data);
		M("mod_".$tablename."_order")->update(array(
			"paotui_id"=>$id
		),"orderid=".$orderid);
		$this->goAll("发布成功，请等待");
		 
		
	}
	
	public function onGet(){
		$tablename=post("tablename","h");
		$orderid=post("orderid","i");
		$paotui=M("mod_paotui")->selectRow("fromapi_params=".$orderid." AND fromapi='".$tablename."'");
		if(empty($paotui)){
			$this->goAll("error",1);
		}
		$status_list=MM("paotui","paotui")->status_list();
		$paotui['status_name']=$status_list[$paotui['status']];
		if($paotui["senderid"]){
			$paotui["sender"]=MM("paotui","paotui_sender")->get($paotui["senderid"]);
		}
		$this->goAll("success",0,array(
			"paotui"=>$paotui
		));
	}
	
	public function onFinish(){
		$tablename=post("tablename","h");
		$orderid=post("orderid","i");
		$paotui=M("mod_paotui")->selectRow("fromapi_params=".$orderid." AND fromapi='".$tablename."'");
		if(empty($paotui)){
			$this->goAll("error",1);
		}
		$status_list=MM("paotui","paotui")->status_list();
		if($paotui["senderid"]){
			$paotui["sender"]=MM("paotui","paotui_sender")->get($paotui["senderid"]);
		}
		//处理结束
		$id=$paotui["id"];
		$order=M("mod_paotui_order")->selectRow("ptid=".$id." AND status=2");
		if(empty($order)){
			$this->goAll("接单数据出错",1);
		}
		M("mod_paotui")->begin();
		M("mod_paotui")->update(array(
			"status"=>3
		),"id=".$id);
		M("mod_paotui_order")->update(array(
			"status"=>3
		),"id=".$order["id"]);
		MM("paotui","paotui_sender")->addMoney(array(
			"senderid"=>$order["senderid"],
			"content"=>"跑腿任务完成，获得赏金".$order["money"]."元,",
			"money"=>$order['money']
		));
		M("mod_paotui")->commit();
		$paotui["status"]=3;
		$paotui['status_name']=$status_list[3];
		$this->goAll("success",0,array(
			"paotui"=>$paotui
		));
	} 
	
}
?>