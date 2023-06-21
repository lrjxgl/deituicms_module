<?php
class olprint_orderControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		 
		$where=" shopid=".SHOPID;
		$url="/moduleshop.php?m=olprint_order";
		$limit=24;
		$start=get("per_page","i");
		$type=get("type","h");
		switch(get('type')){
			
			case "unraty":
				$url.="&type=unraty";
				$where.="   AND status=3 AND israty=0";
				break;
			case "unpay":
				$url.="&type=unpay";
				$where.=" AND status=0 AND ispay=0 ";
				break;
			case "unsend":
				$url.="&type=unsend";
				$where.=" AND status=1 AND ispay=1 ";
				break;	
			case "unreceive":
				$url.="&type=unreceive";
				$where.=" AND status =2  ";
				break;
			case "timeout":
				$url.="&type=timeout";
				$etime=date("Y-m-d H:i:s",time()-1800);
				$where.=" AND status =0 AND ispay=1 AND createtime<'".$etime."'  ";
				break;	
			case "all":
				$type="all";
				$where.=" AND status in(0,1,2,3,4)";
				break;
			default:
				$url.="&type=unsend";
				$where.=" AND status=0 AND ispay=1 ";
				$type="new";
				break;
			
		}
		 
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" orderid DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=MM("olprint","olprint_order")->Dselect($option,$rscount);
		 
		$this->smarty->goAssign(array(
			"list"=>$data,
			"type"=>$type
		));
		$this->smarty->display("olprint_order/index.html");
	}
	
	public function onShow(){
		$orderid=get("orderid","i");
		$order=MM("olprint","olprint_order")->selectRow("orderid=".$orderid);
		if(empty($order)){
			$this->goAll("数据出错",1);
		}
		$statusList=MM("olprint","olprint_order")->statusList();
		$ptypeList=MM("olprint","olprint_ptype")->ptypeList();
		if($order["ispay"]==0 && $order["status"]==0){
			$order["status_name"]="待支付";
		}else{
			$order["status_name"]=$statusList[$order["status"]];
		}
		 
		$order["sendtype_name"]=$order["sendtype"]==0?'自取':"配送";
		 
		$order["ptype_name"]=$ptypeList[$order["ptype"]]["title"];
		$order["fileurl"]=images_site($order["fileurl"]);
		$order["filename"]=basename($fileurl);
		if($order["bookid"]){
			$book=M("mod_olprint_book")->selectRow(array(
				"where"=>"bookid=".$order["bookid"],
				"fields"=>"bookid,title"
			));
		}
		$imgList=[];
		if(!empty($order["imgsdata"])){
			$imgList=explode(",",$order["imgsdata"]);
			foreach($imgList as $k=>$v){
				$imgList[$k]=images_site($v);
			}
		}
		$this->smarty->goAssign(array(
			"order"=>$order,
			"book"=>$book,
			 "imgList"=>$imgList
		));
		$this->smarty->display("olprint_order/show.html");
	}
	
	public function onConfirm(){
		$orderid=get("orderid","i");
		$order=MM("olprint","olprint_order")->selectRow("orderid=".$orderid);
		if($order["status"]!=0){
			$this->goAll("订单已处理",1);
		}
		if($order["ispay"]==0){
			$this->goAll("订单还未支付",1);
		}
		MM("olprint","olprint_order")->update(array(
			"status"=>1
		),"orderid=".$orderid);
		MM("olprint","olprint_shop_notice")->sendUpdateOrder($orderid,"user");
		$this->goAll("订单确认成功");
	}
	
	public function onSend(){
		$orderid=get("orderid","i");
		$order=MM("olprint","olprint_order")->selectRow("orderid=".$orderid);
		if($order["status"]>=2){
			$this->goAll("订单已处理",1);
		}
		if($order["ispay"]==0){
			$this->goAll("订单还未支付",1);
		}
		MM("olprint","olprint_order")->update(array(
			"status"=>2
		),"orderid=".$orderid);
		MM("olprint","olprint_shop_notice")->sendUpdateOrder($orderid,"user");
		$this->goAll("订单发货成功");
	}
	
	public function onCancel(){
		$orderid=get("orderid","i");
		$order=MM("olprint","olprint_order")->selectRow("orderid=".$orderid);
		 
		if($order["status"]!=0){
			$this->goAll("订单无法取消",1);
		}
		MM("olprint","olprint_order")->begin();
		MM("olprint","olprint_order")->update(array(
			"status"=>4
		),"orderid=".$orderid);
		if($order["ispay"]==1){
			//退款到原账户
			if($order['recharge_id']){
				$recharge=M("recharge")->selectRow("id=".$order['recharge_id']);
				$odata=array(
					"tablename"=>"mod_olprint_order",
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
					"content"=>"olprint订单取消，申请退回支付渠道",
					"odata"=>base64_encode(json_encode($odata))
				));
			}else{
				M("user")->addMoney(array(
					"userid"=>$order["userid"],
					"money"=>$order["money"],
					"content"=>"商家取消打印订单，退回".$order["money"]."元"
				));
			}
			
		}
		
		
		MM("olprint","olprint_order")->commit();
		MM("olprint","olprint_shop_notice")->sendUpdateOrder($orderid,"user");
		$this->goAll("订单取消成功");
	}
	
}