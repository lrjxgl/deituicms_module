<?php
class pdd_orderControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		M("login")->checkLogin();
	}
	public function onDefault(){
		
		 
	}
	public function onConfirm(){
		$userid=M("login")->userid;
		$shopid=get_post("shopid","i");
		$shop=MM("pdd","pdd_shop")->get($shopid,"shopid,shopname,imgurl");
		if(empty($shop)){
			$this->goAll("请选择商家",1);
		}
		$cartid=get_post("cartid","i");
		$ispin=get_post("ispin","i");
		$pin_orderid=get_post("pin_orderid","i");
		$where=" userid=".$userid." AND shopid=".$shopid;
		if($cartid){
			$where.=" AND id=".$cartid;
		}
		
		$cartList=MM("pdd","pdd_cart")->Dselect(array(
			"where"=>$where
		));
		if(empty($cartList)){
			$this->goAll("购物车为空，请先选择商品",1,0,"/");
		}
		$total_num=0;
		$total_money=0;
		$goods_money=0;
		$express_money=0;
		$discount_money=0;
		$coupon_money=0;
		$coupon_id=0;
		$weight=0;
		
		if($cartList){
			foreach($cartList as $v){
				$total_num+=$v["amount"];
				if($ispin && $v["pt_price"]){
					
					$goods_money+=$v["amount"]*$v["pt_price"];
				}else{
					$goods_money+=$v["amount"]*$v["price"];
				}
				$weight+=$v["amount"]*$v["weight"];
				if($v["amount"]>$v["total_num"]){
					$this->goAll($v["title"]."当前库存只剩下".$v["total_num"].",库存不足，请减少购买数量或者取消",1);
				}
			}
		}
		
		$total_money=$goods_money;
		//优惠券
		$coupon_id=0;
		$couponList=MM("pdd","pdd_coupon")->UseList($shopid,$userid,$total_money);
		if($couponList){
			$coupon_id=$couponList[0]["id"];
		}
		//快递费
		$addrList=M("user_address")->select(array(
			"where"=>" userid=".$userid
		));
		$paytypeList=pay_type_list(0);
		$paytype=key($paytypeList);
		if(get("user_address_id")){
			$user_address_id=get("user_address_id","i");
		}elseif($addrList){
			$user_address_id=$addrList[0]["id"];
		}else{
			$user_address_id=0;
		}
		$express_money=M("express_fee")->getMoney($user_address_id,$weight);
		$total_money+=$express_money;
		//优惠券
		
		$this->smarty->goAssign(array(
			"cartList"=>$cartList,
			"addrList"=>$addrList,
			"user_address_id"=>$user_address_id,
			"paytypeList"=>$paytypeList,
			"paytype"=>$paytype,
			"goods_money"=>$goods_money,
			"weight"=>$weight,
			"total_money"=>$total_money,
			"total_num"=>$total_num,
			"express_money"=>$express_money,
			"couponList"=>$couponList,
			"coupon_id"=>$coupon_id,
			"shop"=>$shop,
			"ispin"=>$ispin,
			"pin_orderid"=>$pin_orderid
		));
		$this->smarty->display("pdd_order/confirm.html");
	}
	public function onBuy(){
		
		$this->smarty->display("pdd_order/buy.html");
	}
	public function onOrder(){
		$userid=M("login")->userid;
		$user_address_id=post("user_address_id","i");
		$shopid=get_post("shopid","i");
		$addr=M("user_address")->selectRow("id=".$user_address_id);
		if(empty($addr)){
			$this->goAll("请选择地址",1);
		}
		$cartids=post("cartid","i");
		if(empty($cartids)){
			$this->goAll("请选择商品",1);
		}
		
		$cartList=MM("pdd","pdd_cart")->Dselect(array(
			"where"=>" id in("._implode($cartids).")"
		));
		if(empty($cartList)){
			$this->goAll("购物车为空，请选择商品",1);
		}
		$ispin=post("ispin","i");
		$productid=post("productid","i");
		$pin_orderid=post("pin_orderid","i");
		$comment=post("comment","h");
		$total_num=0;
		$total_money=0;
		$goods_money=0;
		$express_money=0;
		$discount_money=0;
		$coupon_money=0;
		$coupon_id=0;
		$weight=0;
		foreach($cartList as $v){
			if($v["amount"]>$v["total_num"]){
				$this->goAll($v["title"]."当前库存只剩下".$v["total_num"].",库存不足，请减少购买数量或者取消",1);
			}
			$total_num+=$v["amount"];
			if($ispin && $v["pt_price"]){
				$goods_money+=$v["amount"]*$v["pt_price"];
			}else{
				$goods_money+=$v["amount"]*$v["price"];
			}
			$weight+=$v["amount"]*$v["weight"];
		}
		$total_money=$goods_money;
		/* Start优惠券 */
		$coupon_id=post('coupon_id','i');
		if($coupon_id){
 
			$coupon=M("mod_pdd_coupon")->selectRow("id=".$coupon_id);
			$etime=strtotime($coupon['end_time']);
			if(!$coupon || $goods_money<$coupon['lower_money'] || $etime<time()){
				//失效
			}else{
				$coupon_user=M("mod_pdd_coupon_user")->selectRow("coupon_id=".$coupon_id." AND status=0 AND userid=".$userid);			
				if($coupon_user || $coupon["typeid"]==1){
					$coupon_money=$coupon['money'];
					if($coupon_user){
						M("mod_pdd_coupon_user")->update(array("status"=>1),"id=".$coupon_user['id']);
					}				
					M("mod_pdd_coupon")->update(array("use_num"=>$coupon['use_num']+1),"id=".$coupon_id);
					$total_money=$total_money-$coupon_money;
				}else{
					$coupon_id=0;
				}	
			}
								
		}else{
			$coupon_money=0;
		}
		/* End 优惠券*/
		//快递费
		$express_money=M("express_fee")->getMoney($user_address_id,$weight);
		$total_money+=$express_money;
		//生成订单
		$orderno="mpdd".M("maxid")->get();
		$daySn=M("daysn")->get(array(
			"tablename"=>"pdd"
		));
		$paytype=post("paytype","h");
		//生成订单
		$createtime=date("Y-m-d H:i:s");
		MM("pdd","pdd_order")->begin();
		$orderid=MM("pdd","pdd_order")->insert(array(
			"shopid"=>$shopid,
			"money"=>$total_money,
			"goods_money"=>$goods_money,
			"express_money"=>$express_money,
			"coupon_id"=>$coupon_id,
			"coupon_money"=>$coupon_money,
			"total_num"=>$total_num,
			"weight"=>$weight,
			"orderno"=>$orderno,
			"createtime"=>$createtime,
			"userid"=>$userid,
			"user_address_id"=>$user_address_id,
			"daySn"=>$daySn,
			"paytype"=>$paytype,
			"comment"=>$comment,
			"ispin"=>$ispin,
			"pin_num"=>$ispin?1:0,
			"productid"=>$ispin?$cartList[0]["productid"]:0,
			"pin_orderid"=>$pin_orderid
		));
		
		//生成订单产品列表
		$prolist=array();
		foreach($cartList as $v){
			if($ispin && $v["pt_price"]){
				$v["price"]=$v["pt_price"];
			}
			$prolist[]=$v;
			M("mod_pdd_order_product")->insert(array(
				"shopid"=>$shopid,
				"orderid"=>$orderid,
				"createtime"=>$createtime,
				"userid"=>$userid,
				"productid"=>$v["productid"],
				"ksid"=>$v["ksid"],
				"price"=>$v["price"],
				"amount"=>$v["amount"]
			));
		}
		//生成订单地址
		M("mod_pdd_order_address")->insert(array(
			"shopid"=>$shopid,
			"orderid"=>$orderid,
			"createtime"=>$createtime,
			"userid"=>$userid,
			"truename"=>$addr["truename"],
			"telephone"=>$addr["telephone"],
			"address"=>$addr["pct_address"],
			"province_id"=>$addr["province_id"],
			"city_id"=>$addr["city_id"],
			"town_id"=>$addr["town_id"],
		));
		//生成订单 地址和商品
		$order_data=array(
			"address"=>array(
				"truename"=>$addr['truename'],
				"telephone"=>$addr['telephone'],				 
				"address"=>$addr['pct_address']
			),
			"prolist"=>$prolist
		);
		M("mod_pdd_order_data")->insert(array(
			"shopid"=>$shopid,
			"orderid"=>$orderid,
			"userid"=>$userid,
			"createtime"=>date("Y-m-d H:i:s"),
			"updatetime"=>date("Y-m-d H:i:s"),
			"content"=>base64_encode(json_encode($order_data))
		));
		//减少库存
		foreach($cartList as $v){
			if($v["ksid"]){
				MM("pdd","pdd_product_ks")->changenum("total_num",-$v["amount"],"id=".$v["ksid"]);
			}else{
				MM("pdd","pdd_product")->changenum("total_num",-$v["amount"],"id=".$v["productid"]);
			}
		}	
		//清除购物车
		MM("pdd","pdd_cart")->delete(" id in("._implode($cartids).")");
		MM("pdd","pdd_order")->commit();
		
		$action="pay";
		$rdata=array(
			"action"=>$action,
			"orderid"=>$orderid
		);
		if(!$data['ispay']){
			$_GET["orderid"]=$orderid;
			$res=$this->onPay(1);
			$rdata['payurl']=$res['payurl'];
			$rdata['orderno']=$res['orderno'];
		}else{
			if($ispin){
				MM("pdd","pdd_order")->pinOrder($orderid);
			}
			$rdata=array(
				"action"=>"success",
				"orderid"=>$orderid
			);
		}
		
		
		$this->goAll("下单成功",0,$rdata);	
	}
	/***生成支付*****/
	public function onPay($return=0){
		$userid=M("login")->userid;
		$orderno="Re".M("maxid")->get();
		$orderid=get("orderid","i");
		$order=MM("pdd","pdd_order")->selectRow("orderid=".$orderid);
		//生成支付
		
		$backurl=get_post("backurl","x");
		if($backurl==""){
			$backurl="/module.php?m=pdd_order&a=success";
		}
		$pay_type=INWEIXIN?"wxpay":"alipay";
		$orderdata=array(
			"table"=>"plugin",
			"callback"=>'
				MM("pdd","pdd_order")->update(array(
					"ispay"=>1,
					"recharge_id"=>"$recharge_id",
					"paytype"=>"'.$paytype.'",
				),"orderid='.$orderid.'");
				MM("pdd","pdd_order")->pinOrder('.$orderid.');
			',
			"url"=>$backurl
		);
		$orderdata=base64_encode(json_encode($orderdata)); 
		$orderinfo=date("Y-m-d H:i:s")."购买商品";
		$order_product=date("Y-m-d H:i:s")."购买商品";
		$fromapp=get("fromapp");
		$money= $order['money'];
		$openid=get('openid','h');
		//固定支付
		$rechargeid=M("recharge")->insert(array(
			"orderno"=>$orderno,
			"userid"=>$userid,
			"money"=>$money,
			"pay_type"=>$pay_type,
			"dateline"=>time(),
			"openid"=>$openid,
			"orderinfo"=>$orderinfo, 
			"orderdata"=>$orderdata,
			"status"=>2,
		));
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
		$this->goALl("正在前往支付",0,$redata,$url);
	}
	
	public function onSuccess(){
		$orderid=get("orderid","i");
		$order=MM("pdd","pdd_order")->selectRow("orderid=".$orderid);
		//获取拼团人数
		$need_num=0;
		$pt_ewm="";
		if($order["ispin"]){
			$product=MM("pdd","pdd_product")->selectRow(array(
				"where"=>"id=".$order["productid"],
				"fields"=>"pt_open,pt_min"
			));
			$need_num=$product["pt_min"]-$order["pin_num"];
			$pturl=HTTP_HOST."/module.php?m=pdd_product&a=show&id=".$order["productid"]."&orderid=".$orderid;
			$pt_ewm=HTTP_HOST."/index.php?m=qrcode&content=".urlencode($pturl)."&title=".urlencode("快来和我一起拼团吧");
		}
		
		$this->smarty->goAssign(array(
			"need_num"=>$need_num,
			"order"=>$order,
			"pt_ewm"=>$pt_ewm
		));
		$this->smarty->display("pdd_order/success.html");
	}
	public function onMy(){
		$userid=M("login")->userid;
		$where=" userid=".$userid;
		$url="/module.php?m=pdd_order&a=my";
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
			case "unpin":
				$url.="&type=unpin";
				$where.=" AND status =0 AND ispay=1 AND pin_success=0 AND ispin=1 ";
				break;
			default:
				$type="all";
				$where.=" AND status in(0,1,2,3,10)";
				break;
			
		}
		 
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" orderid DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=MM("pdd","pdd_order")->select($option,$rscount);
		if($data){
			foreach($data as $v){
				$oids[]=$v["orderid"];
				$shopids[]=$v["shopid"];
			}
			$sps=MM("pdd","pdd_shop")->getListByIds($shopids);
			$ods=MM("pdd","pdd_order_data")->getListByOrderIds($oids);
			foreach($data as $k=>$v){
				$v["shop"]=$sps[$v["shopid"]];
				$v['addr']=$ods[$v['orderid']]['address'];
				$v['prolist']=$ods[$v['orderid']]['prolist'];
				$v["status_name"]=MM("pdd","pdd_order")->getStatus($v);
				$v["timeago"]=timeago(strtotime($v["createtime"]));
				$data[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$data,
			"type"=>$type
		));
		$this->smarty->display("pdd_order/my.html");
	}
	public function onShow(){
		$orderid=get("orderid","i");
		$order=MM("pdd","pdd_order")->selectRow("orderid=".$orderid);
		$orderdata=MM("pdd","pdd_order_data")->get($orderid);
		$shop=MM("pdd","pdd_shop")->get($order["shopid"]); 
		$order["status_name"]=MM("pdd","pdd_order")->getStatus($order);
		$order["timeago"]=timeago(strtotime($order["createtime"]));
		$this->smarty->goAssign(array(
			"order"=>$order,
			"shop"=>$shop,
			"addr"=>$orderdata["address"],
			"prolist"=>$orderdata["prolist"]
		));
		$this->smarty->display("pdd_order/show.html");
	}
	/**取消**/
	public function onCancel(){
		$orderid=get("orderid","i");
		$order=MM("pdd","pdd_order")->selectRow("orderid=".$orderid);
		$userid=M("login")->userid;
		if($order["userid"]!=$userid){
			$this->goAll("您无权限取消订单",1);
		}
	 
		if($order["status"]!=0){
			$this->goAll("该订单已经处理了",1);
		}
		MM("pdd","pdd_order")->begin();
		MM("pdd","pdd_order")->update(array(
			"status"=>10
		),"orderid=".$orderid);
		if($order["ispay"]==1){
			//退款到原账户
			$recharge=M("recharge")->selectRow("id=".$order['recharge_id']);
			$odata=array(
				"tablename"=>"mod_pdd_order",
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
				"content"=>"pdd订单取消，申请退回支付渠道",
				"odata"=>base64_encode(json_encode($odata))
			));
		}
		//增加商品库存
		$proList=MM("pdd","pdd_order_product")->select(array(
			"where"=>"orderid=".$orderid
		));
		if($proList){
			foreach($proList as $v){
				if($v["ksid"]){
					MM("pdd","pdd_product_ks")->changenum("total_num",$v["amount"],"ksid=".$v["ksid"]);
				}else{
					MM("pdd","pdd_product")->changenum("total_num",$v["amount"],"id=".$v["productid"]);
				}	
			}
		}
		MM("pdd","pdd_order")->commit();
		$this->goAll("取消成功");
	}
	/**
	 * 收货
	 */
	
	public function onReceive(){
		$orderid=get("orderid","i");
		$order=MM("pdd","pdd_order")->selectRow("orderid=".$orderid);
		$userid=M("login")->userid;
		if($order["userid"]!=$userid){
			$this->goAll("您无权限取消订单",1);
		}
		if($order["ispay"]==0){
			$this->goAll("该订单还未支付",1);
		}
		if($order["status"]>2){
			$this->goAll("该订单暂时无法收货",1);
		}
		MM("pdd","pdd_order")->begin();
		MM("pdd","pdd_order")->update(array(
			"status"=>3,
			"isreceived"=>1
		),"orderid=".$orderid);
		//分配商家收益
		MM("pdd","pdd_shop_money")->addMoney(array(
			"shopid"=>$order["shopid"],
			"income"=>$order["money"],
			"balance"=>$order["money"],
			"content"=>"订单完成获得了".$order["money"]."元收入，"
		));
		MM("pdd","pdd_order")->commit();
		$this->goAll("操作成功");
	}
	/**
	 * 评价
	 */
	public function onRaty(){
		$orderid=get("orderid","i");
		$order=MM("pdd","pdd_order")->selectRow("orderid=".$orderid);
		$userid=M("login")->userid;
		if($order["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		$sql="select a.id,a.raty_grade,a.productid,b.title,b.imgurl from ".table("mod_pdd_order_product")." as a "
			." left join ".table("mod_pdd_product")." as b "
			." on a.productid=b.id "
			." where a.orderid=".$orderid;
		$proList=MM("pdd","pdd_order")->getAll($sql);
		if($proList){
			foreach($proList as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$proList[$k]=$v;
			}
		}
		$raty=MM("pdd","mod_pdd_order_raty")->selectRow("orderid=".$orderid);
		if(empty($raty)){
			$raty=array(
				"raty_quality"=>9,
				"raty_service"=>9,
				"raty_express"=>9
			);
		}
		$this->smarty->goAssign(array(
			"proList"=>$proList,
			"order"=>$order,
			"raty"=>$raty
		));	
		$this->smarty->display("pdd_order/raty.html");
	}
	
	public function onRatySave(){
		$userid=M("login")->userid;
		$orderid=get_post("orderid","i");
		$order=MM("pdd","pdd_order")->selectRow("orderid=".$orderid);
		if($order["ispay"]==0){
			$this->goAll("该订单还未支付",1);
		}
		if($order["status"]!=3){
			$this->goAll("该订单暂时无法评价",1);
		}
		if(!$order["israty"]){
			MM("pdd","pdd_order")->update(array(
				"israty"=>1
			),"orderid=".$orderid);
			$ratyData=M("mod_pdd_order_raty")->postData();
			$ratyData["userid"]=$userid;
			$ratyData["createtime"]=date("Y-m-d H:i:s");
			$ratyData["shopid"]=$order["shopid"];
			M("mod_pdd_order_raty")->insert($ratyData);
			$ratyPros=post("ratyPros","i");
			if(!empty($ratyPros)){
				foreach($ratyPros as $k=>$v){
					M("mod_pdd_order_product")->update(array(
						"raty_grade"=>intval($v),
						"shopid"=>$order["shopid"]
					),"orderid=".$orderid." AND id=".intval($k));
				}
			}
		}
		$this->goAll("评价成功");
	}
	
}