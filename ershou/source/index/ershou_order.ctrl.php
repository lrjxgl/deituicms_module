<?php
class ershou_orderControl extends skymvc{
	public function onInit(){
		M("login")->checkLogin();
	}
	public function onDefault(){
		
	}
	
	public function onConfirm(){
		
		$userid=M("login")->userid;
		$productid=get("productid","i");
		$data=M("mod_ershou_product")->selectRow(array("where"=>"productid=".$productid));
		//判断是否已经喜爱
		$love=M("love")->selectRow("tablename='mod_ershou_product' AND userid=".$userid." AND objectid=".$productid);
		 
		if(empty($love)){
			M("love")->insert(array(
				"tablename"=>"mod_ershou_product",
				"userid"=>$userid,
				"objectid"=>$productid
			));
			M("mod_ershou_product")->update(array(
				"love_num"=>$data["love_num"]+1
			),"productid=".$productid);
		}
		
		$data["imgurl"]=images_site($data["imgurl"]);
		$addrList=M("user_address")->select(array(
			"where"=>" userid=".$userid
		));
		$user_address_id=0;
		if(!empty($addrList)){
			$user_address_id=$addrList[0]["id"];
		}
		//报价
		$total_money=$data["price"];
		$bjid=get_post("bjid","i");
		$baojia=[];
		if($bjid){
			$baojia=M("mod_ershou_baojia")->selectRow("id=".$bjid." AND status=1");
			$total_money=$baojia["money"];
		}
		
		$this->smarty->goAssign(array(
			"data"=>$data,
			"addrList"=>$addrList,
			"user_address_id"=>$user_address_id,
			"baojia"=>$baojia,
			"total_money"=>$total_money
		));
		$this->smarty->display("ershou_order/confirm.html");
	}
	
	public function onOrder(){
		$userid=M("login")->userid;
		$productid=post("productid","i");
		$data=M("mod_ershou_product")->selectRow(array("where"=>"productid=".$productid));
		$user_address_id=post("user_address_id","i");
		$addr=M("user_address")->selectRow("id=".$user_address_id);
		$shop=MM("ershou","ershou_shop")->getShopByUserid($data["userid"]);
		$bjid=get_post("bjid","i");
		$money=$data["price"];
		if($bjid){
			$baojia=M("mod_ershou_baojia")->selectRow("id=".$bjid." AND status=1");
			$money=$baojia["money"];
		}
		$orderid=M("mod_ershou_order")->insert(array(
			"userid"=>$userid,
			"shopid"=>$shop["shopid"],
			"productid"=>$productid,
			"user_address_id"=>$user_address_id,
			"nickname"=>$addr["nickname"],
			"telephone"=>$addr["telephone"],
			"address"=>$addr["address"],
			"money"=>$money,
			"createtime"=>date("Y-m-d H:i:s")
		));
		
		$gopay=1;
		if($gopay==1){
			$_GET["orderid"]=$orderid;
			$payData=$this->onPay(true);
			$pdata=array(
				"orderid"=>$orderid,
				"gopay"=>$gopay,
				"payurl"=>$payData["payurl"],
				"action"=>"pay",
				"orderno"=>$payData["orderno"],
				"url"=>"/module.php?m=ershou_order&a=pay&orderid=".$orderid
			);
		}else{
			$pdata=array(
				"orderid"=>$orderid,
				"gopay"=>$gopay,
				"url"=>"/module.php?m=ershou_order&a=pay&orderid=".$orderid
			);
		}
		
		$this->goAll("感谢您的支持，请继续支付订单",0,$pdata);
	}
	
	public function onPay($return=false){
		$orderid=get('orderid','i');
		$userid=M("login")->userid;
		$order=M("mod_ershou_order")->selectRow("orderid=".$orderid);
		$productid=$order['productid'];
		if($order['ispay']){
			$this->goAll("已经支付过了",1);
		}
		$product=M("mod_ershou_product")->selectRow("productid=".$productid);
		if($product['status']!=1){
			$this->goAll("该产品已下线",1);
		}
		 
		if(ALIPAY!=1 && WXPAY!=1){
			$this->goAll("支付未配置无法进行支付操作",1);
		}
		if(INWEIXIN==true && WXPAY==1){
			$pay_type="wxpay";
		}else{
			$pay_type="alipay";
		}
		 
		$order_product=cutstr($product["description"],24);
		$orderno="re".M("maxid")->get();
		$backurl="/module.php?m=ershou_order&a=success&productid=".$productid;
		$orderdata=array(
			"table"=>"plugin",
			"callback"=>'
				MM("ershou","ershou_order")->paySuccess(array(
					"ispay"=>1,
					"recharge_id"=>"$recharge_id",
					"paytype"=>"'.$pay_type.'",
				),'.$orderid.');
						
			',
			"url"=>$backurl
		);
		$orderdata=base64_encode(json_encode($orderdata));
		$order_price=$order['money'];
		/*****插入充值表******/
		M("recharge")->insert(array(
			"userid"=>$userid,
			"money"=>$order_price,
			"pay_type"=>$pay_type,
			"orderno"=>$orderno,
			"orderinfo"=>$ershou['title'], 
			"type_id"=>1,
			"tablename"=>"",
			"dateline"=>time(),
			"status"=>2,
			
			"orderdata"=>$orderdata,
		));
		
		/*插入充值表结束*/
		
		$bank_type="";
		
		$url=HTTP_HOST."/index.php?m=recharge_{$pay_type}&a=go";
		$url.="&orderno=$orderno";
		$url.="&bank_type=".$bank_type;
		$url.="&order_product=".urlencode($order_product);
		$url.="&order_price=".$money;
		$url.="&order_info=".urlencode($order_info);
		$url.="&backurl=".urlencode($backurl);
		$redata=array(
			"payurl"=>$url,
			"action"=>"pay",
			"orderno"=>$orderno
		);
		if($return){
			return $redata;
		}
		//end 固定支付
		if(!get("ajax")){
			header("Location: ".$url);
			exit;
		}
		$this->goALl("正在前往支付",0,$redata,$url);
		exit;
	}
	public function onSuccess(){
		
		$this->smarty->display("ershou/success.html");
	}
	
	public function onMy(){
		$userid=M("login")->userid;
		$where=" userid=".$userid;
		 
		$type=get("type","h");
		switch($type){
			case "unpay":
				$where.=" AND status=0 AND ispay=0 ";
				break;
			case "unraty":
				$where.=" AND status=3 AND israty=0";
				break;
			 
			case "unreceive":
				$where.=" AND status in(0,1,2) AND ispay=1 ";
				break;
			default:
				$where.=" AND status in(0,1,2,3,4) ";
				break;
		}
		$option=array(
			"where"=>$where,
			"order"=>"orderid DESC"
		);
		$data=MM("ershou","ershou_order")->select($option);
		$statuslist=MM("ershou","ershou_order")->statuslist();
		if($data){
			foreach($data as $v){
				$ids[]=$v['productid'];
				 
			}
			$ershous=MM("ershou","ershou_product")->getListByIds($ids);
			 
			foreach($data as $k=>$v){
				 
				$v['ershou']=$ershous[$v['productid']];
			 	$v['timeago']=timeago(strtotime($v['createtime']));
				$v['status_name']=MM("ershou","ershou_order")->getStatus($v);
				$data[$k]=$v;
			}
		}
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$this->smarty->goAssign(array(
			"list"=>$data,
			"per_page"=>$per_page,
			"statuslist"=>$statuslist
			
		));
		
		$this->smarty->display("ershou_order/my.html");
	}
	public function onShow(){
		$orderid=get_post("orderid","i");
		$order=MM("ershou","ershou_order")->selectRow("orderid=".$orderid);
		$discount_money=0;
		$userid=M("login")->userid;
		if($order["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		$product=M("mod_ershou_product")->selectRow(array(
			"where"=>" productid=".$order["productid"],
			"fields"=>"productid,imgurl,description,price,sold_num"
		));
		 
		$product["imgurl"]=images_site($product["imgurl"]);
		 
		$order["status_name"]=MM("ershou","ershou_order")->getStatus($order);
		$this->smarty->goAssign(array(
			"order"=>$order,
			"product"=>$product,
			"ordercode"=>$ordercode,
			"orderCodeEwm"=>$orderCodeEwm,
			"discount_money"=>$discount_money
		));
		$this->smarty->display("ershou_order/show.html");
	}
	public function onReceive(){
		$userid=M("login")->userid;
		$orderid=get_post("orderid","i");
		$order=MM("ershou","ershou_order")->selectRow("orderid=".$orderid);
		if($order["ispay"]==0){
			$this->goAll("该订单还未支付",1);
		}
		if($order["status"]>2){
			$this->goAll("该订单已处理",1);
		}
		if($order["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		MM("ershou","ershou_order")->begin();
		MM("ershou","ershou_order")->finish($orderid);
		MM("ershou","ershou_order")->commit(); 
	
		$this->goAll("订单收货完成，请评价一下吧");
	}
	
	public function onRaty(){
		$orderid=get_post("orderid","i");
		$order=MM("ershou","ershou_order")->selectRow("orderid=".$orderid);
		$product=MM("ershou","ershou_product")->selectRow("productid=".$order["productid"]);
		$product["imgurl"]=images_site($product["imgurl"]);
		$raty=M("mod_ershou_order_raty")->selectRow("orderid=".$orderid);
		$this->smarty->goAssign(array(
			"order"=>$order,
			"product"=>$product,
			"raty"=>$raty
		));
		$this->smarty->display("ershou_order/raty.html");
	}
	public function onRatySave(){
		$userid=M("login")->userid;
		$orderid=get_post("orderid","i");
		$order=MM("ershou","ershou_order")->selectRow("orderid=".$orderid);
		if($order["ispay"]==0){
			$this->goAll("该订单还未支付",1);
		}
		if($order["status"]!=3){
			$this->goAll("该订单暂时无法评价",1);
		}
		if($order["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		if(!$order["israty"]){
			MM("ershou","ershou_order")->update(array(
				"israty"=>1
			),"orderid=".$orderid);
			$ratyData=M("mod_ershou_order_raty")->postData();
			$ratyData["userid"]=$userid;
			$ratyData["createtime"]=date("Y-m-d H:i:s");
			$ratyData["productid"]=$order["productid"];
			$raty=M("mod_ershou_order_raty")->selectRow("orderid=".$orderid);
			if($raty){
				M("mod_ershou_order_raty")->update($ratyData,"orderid=".$orderid);
			}else{
				M("mod_ershou_order_raty")->insert($ratyData);
			}
			
			 
		}
		$this->goAll("评价成功");
	}
	
}