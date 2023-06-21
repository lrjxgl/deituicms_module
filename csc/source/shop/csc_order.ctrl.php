<?php
class csc_orderControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		 
		$where=" shopid=".SHOPID;
		$url="/moduleshop.php?m=csc_order";
		$limit=24;
		$start=get("per_page","i");
		$type=get("type","h");
		switch(get('type')){
			case "new":
				$url.="&type=new";
				$where.="   AND ispay=1 AND status=0";
				break;
			case "unraty":
				$url.="&type=unraty";
				$where.="   AND isreceived=1 AND israty=0";
				break;
			case "unpay":
				$url.="&type=unpay";
				$where.=" AND status=0 AND ispay=0 ";
				break;
			case "spei":
				$url.="&type=spei";
				$where.=" AND status in(0,1) AND ispay=1 AND senderid=0 ";
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
		$data=MM("csc","csc_order")->select($option,$rscount);
		if($data){
			foreach($data as $v){
				$oids[]=$v["orderid"];
			}
			$ods=MM("csc","csc_order_data")->getListByOrderIds($oids);
			foreach($data as $k=>$v){
				$v['addr']=$ods[$v['orderid']]['address'];
				$v['prolist']=$ods[$v['orderid']]['prolist'];
				$v["status_name"]=MM("csc","csc_order")->getStatus($v);
				$v["timeago"]=timeago(strtotime($v["createtime"]));
				$data[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$data,
			"type"=>$type
		));
		$this->smarty->display("csc_order/index.html");
	}
	
	public function onShow(){
		$orderid=get("orderid","i");
		$order=MM("csc","csc_order")->selectRow("orderid=".$orderid);
		$orderdata=MM("csc","csc_order_data")->get($orderid);
		 
		$order["status_name"]=MM("csc","csc_order")->getStatus($order);
		$order["timeago"]=timeago(strtotime($order["createtime"]));
		$sender=false;
		if($order["senderid"]){
			$sender=MM("csc","csc_sender")->get($order["senderid"]);
		}
		$change=M("mod_csc_order_change")->selectRow("orderid=".$orderid);
		$sds=M("mod_csc_sender")->select(array(
			"where"=>" shopid=".SHOPID." AND status=1 "
		));
		$this->smarty->goAssign(array(
			"order"=>$order,
			"addr"=>$orderdata["address"],
			"prolist"=>$orderdata["prolist"],
			"sender"=>$sender,
			"change"=>$change,
			"sds"=>$sds
		));
		$this->smarty->display("csc_order/show.html");
	}
	
	public function onConfirm(){
		$orderid=get("orderid","i");
		$order=MM("csc","csc_order")->selectRow("orderid=".$orderid);
		if($order["status"]!=0){
			$this->goAll("订单已处理",1);
		}
		if($order["ispay"]==0){
			$this->goAll("订单还未支付",1);
		}
		MM("csc","csc_order")->update(array(
			"status"=>1
		),"orderid=".$orderid);
		MM("csc","csc_shop_notice")->sendUpdateOrder($orderid,"user");
		$this->goAll("订单确认成功");
	}
	
	public function onSend(){
		$orderid=get("orderid","i");
		$order=MM("csc","csc_order")->selectRow("orderid=".$orderid);
		if($order["status"]>=2){
			$this->goAll("订单已处理",1);
		}
		if($order["ispay"]==0){
			$this->goAll("订单还未支付",1);
		}
		MM("csc","csc_order")->update(array(
			"status"=>2
		),"orderid=".$orderid);
		MM("csc","csc_shop_notice")->sendUpdateOrder($orderid,"user");
		$this->goAll("订单发货成功");
	}
	
	public function onFinish(){
		$orderid=get("orderid","i");
		$order=MM("csc","csc_order")->selectRow("orderid=".$orderid);
		if($order["status"]!=2){
			$this->goAll("订单暂时无法处理",1);
		}
		if($order["ispay"]==0){
			$this->goAll("订单还未支付",1);
		}
		MM("csc","csc_order")->begin();
		MM("csc","csc_order")->finish($orderid,$order);
		MM("csc","csc_order")->commit();
		$this->goAll("订单完成");
	}
	
	public function onCancel(){
		$orderid=get("orderid","i");
		$order=MM("csc","csc_order")->selectRow("orderid=".$orderid);
		if($order["status"]!=0){
			$this->goAll("订单无法取消",1);
		}
		MM("csc","csc_order")->begin();
		MM("csc","csc_order")->update(array(
			"status"=>4
		),"orderid=".$orderid);
		if($order["ispay"]==1){
			//退款到原账户
			$recharge=M("recharge")->selectRow("id=".$order['recharge_id']);
			$odata=array(
				"tablename"=>"mod_csc_order",
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
				"content"=>"csc订单取消，申请退回支付渠道",
				"odata"=>base64_encode(json_encode($odata))
			));
		}
		
		//增加商品库存
		$proList=MM("csc","csc_order_product")->select(array(
			"where"=>"orderid=".$orderid
		));
		if($proList){
			foreach($proList as $v){
				if($v["ksid"]){
					MM("csc","csc_product_ks")->changenum("total_num",$v["amount"],"ksid=".$v["ksid"]);
				}else{
					MM("csc","csc_product")->changenum("total_num",$v["amount"],"id=".$v["productid"]);
				}	
			}
		}
		MM("csc","csc_order")->commit();
		MM("csc","csc_shop_notice")->sendUpdateOrder($orderid,"user");
		$this->goAll("订单取消成功");
	}
	
	public function onChangeMoney(){
		$typeid=get("typeid","i");
		$money=get("money","h");
		if($typeid==1){
			if($money>0){
				$this->goAll("多还差价只能小于0",1);
			}
		}else{
			if($money<0){
				$this->goAll("少补差价只能大于0",1);
			}
		}
		$content=get("content","h");
		$orderid=get("orderid","i");
		$order=MM("csc","csc_order")->selectRow("orderid=".$orderid);
		if($order["status"]>2){
			$this->goAll("订单无法操作",1);
		}
		if($order["ischange"]){
			$this->goAll("该订单已经申请补单了",1);
		}
		M("mod_csc_order")->update(array(
			"ischange"=>1
		),"orderid=".$orderid);
		$id=M("mod_csc_order_change")->insert(array(
			"orderid"=>$orderid,
			"typeid"=>$typeid,
			"userid"=>$order["userid"],
			"shopid"=>$order["shopid"],
			"content"=>$content,
			"money"=>$money,
			"dateline"=>time()
		));
		$this->goAll("保存成功");
	}
	
}