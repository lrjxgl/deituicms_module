<?php
class mmjz_orderControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		 
		$where=" shopid=".SHOPID;
		$url="/moduleshop.php?m=mmjz_order";
		$limit=24;
		$start=get("per_page","i");
		$type=get("type","h");
		switch(get('type')){
			case "unraty":
				$url.="&type=unraty";
				$where.="   AND isreceived=1 AND israty=0";
				break;
			case "unpay":
				$url.="&type=unpay";
				$where.=" AND status=0 AND ispay=0 ";
				break;
			case "unsend":
				$url.="&type=unsend";
				$where.=" AND status in(0,1) AND ispay=1 ";
				break;	
			case "unreceive":
				$url.="&type=unreceive";
				$where.=" AND status =2 AND ispay=1 AND isreceived=0 ";
				break;
			default:
				$type="all";
				$where.=" AND status in(0,1,2,3)";
				break;
			
		}
		 
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" orderid DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=MM("mmjz","mmjz_order")->select($option,$rscount);
		if($data){
			foreach($data as $v){
				$oids[]=$v["orderid"];
			}
			$ods=MM("mmjz","mmjz_order_data")->getListByOrderIds($oids);
			foreach($data as $k=>$v){
				$v['addr']=$ods[$v['orderid']]['address'];
				$v['prolist']=$ods[$v['orderid']]['prolist'];
				$v["status_name"]=MM("mmjz","mmjz_order")->getStatus($v);
				$v["timeago"]=timeago(strtotime($v["createtime"]));
				$data[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$data,
			"type"=>$type
		));
		$this->smarty->display("mmjz_order/index.html");
	}
	
	public function onShow(){
		$orderid=get("orderid","i");
		$order=MM("mmjz","mmjz_order")->selectRow("orderid=".$orderid);
		$orderdata=MM("mmjz","mmjz_order_data")->get($orderid);
		 
		$order["status_name"]=MM("mmjz","mmjz_order")->getStatus($order);
		$order["timeago"]=timeago(strtotime($order["createtime"]));
		$this->smarty->goAssign(array(
			"order"=>$order,
			"addr"=>$orderdata["address"],
			"prolist"=>$orderdata["prolist"]
		));
		$this->smarty->display("mmjz_order/show.html");
	}
	
	public function onConfirm(){
		$orderid=get("orderid","i");
		$order=MM("mmjz","mmjz_order")->selectRow("orderid=".$orderid);
		if($order["status"]!=0){
			$this->goAll("订单已处理",1);
		}
		if($order["ispay"]==0){
			$this->goAll("订单还未支付",1);
		}
		MM("mmjz","mmjz_order")->update(array(
			"status"=>1
		),"orderid=".$orderid);
		MM("mmjz","mmjz_shop_notice")->sendUpdateOrder($orderid,"user");
		$this->goAll("订单确认成功");
	}
	
	public function onSend(){
		$orderid=get("orderid","i");
		$order=MM("mmjz","mmjz_order")->selectRow("orderid=".$orderid);
		if($order["status"]>=2){
			$this->goAll("订单已处理",1);
		}
		if($order["ispay"]==0){
			$this->goAll("订单还未支付",1);
		}
		MM("mmjz","mmjz_order")->update(array(
			"status"=>2
		),"orderid=".$orderid);
		MM("mmjz","mmjz_shop_notice")->sendUpdateOrder($orderid,"user");
		$this->goAll("订单发货成功");
	}
	
	public function onCancel(){
		$orderid=get("orderid","i");
		$order=MM("mmjz","mmjz_order")->selectRow("orderid=".$orderid);
		if($order["status"]!=0){
			$this->goAll("订单无法取消",1);
		}
		MM("mmjz","mmjz_order")->begin();
		MM("mmjz","mmjz_order")->update(array(
			"status"=>4
		),"orderid=".$orderid);
		if($order["ispay"]==1){
			//退款到原账户
			$recharge=M("recharge")->selectRow("id=".$order['recharge_id']);
			$odata=array(
				"tablename"=>"mod_mmjz_order",
				"userid"=>$recharge['userid'],
				"money"=>$recharge['money'],
				"createtime"=>$recharge['createtime'],
				"recharge_orderno"=>$recharge['orderno'],
				"recharge_pay_orderno"=>$recharge['pay_orderno'],
				"recharge_id"=>$order['recharge_id'],
			);
			M("refund_apply")->insert(array(
				"userid"=>$order['userid'],
				 
				"paytype"=>$recharge['pay_type'],
				"createtime"=>date("Y-m-d H:i:s"),
				"recharge_orderno"=>$recharge['orderno'],
				"recharge_pay_orderno"=>$recharge['pay_orderno'],
				"money"=>$order['money'],
				"recharge_id"=>$order['recharge_id'],
				"content"=>"mmjz订单取消，申请退回支付渠道",
				"odata"=>base64_encode(json_encode($odata))
			));
		}
		
		//增加商品库存
		$proList=MM("mmjz","mmjz_order_product")->select(array(
			"where"=>"orderid=".$orderid
		));
		if($proList){
			foreach($proList as $v){
				if($v["ksid"]){
					MM("mmjz","mmjz_product_ks")->changenum("total_num",$v["amount"],"ksid=".$v["ksid"]);
				}else{
					MM("mmjz","mmjz_product")->changenum("total_num",$v["amount"],"id=".$v["productid"]);
				}	
			}
		}
		MM("mmjz","mmjz_order")->commit();
		MM("mmjz","mmjz_shop_notice")->sendUpdateOrder($orderid,"user");
		$this->goAll("订单取消成功");
	}
	
}