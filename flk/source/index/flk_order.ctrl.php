<?php
class flk_orderControl extends skymvc{
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
		$shopid=get("shopid","i");
		$shop=MM("flk","flk_shop")->get($shopid,"shopid,shopname,imgurl,flk_discount,flk_maxmoney,flk_new,express_money,send_startprice");
		if(empty($shop)){
			$this->goAll("请选择商家",1);
		}
		$cartid=get_post("cartid","i");
		$where=" userid=".$userid." AND shopid=".$shopid;
		if($cartid){
			$where.=" AND id=".$cartid;
		}
		
		$cartList=MM("flk","flk_cart")->Dselect(array(
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
				$goods_money+=$v["amount"]*$v["price"];
				$weight+=$v["amount"]*$v["weight"];
				if($v["amount"]>$v["total_num"]){
					$this->goAll($v["title"]."当前库存只剩下".$v["total_num"].",库存不足，请减少购买数量或者取消",1);
				}
			}
		}
		
		$total_money=$goods_money;
		if($total_money<$shop["send_startprice"]){
			$this->goAll("未达到起送价".$shop["send_startprice"]."元",1);
		}
		//优惠券
		$coupon_id=0;
		$couponList=MM("flk","flk_coupon")->UseList($shopid,$userid,$total_money);
		if($couponList){
			$coupon_id=$couponList[0]["id"];
		}
		//快递费
		$addrList=M("user_address")->select(array(
			"where"=>" userid=".$userid." AND status=1 "
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
		
		$sendtype="shop";
		$express_money=MM("flk","flk_express_fee")->getMoney($user_address_id,$weight,$shopid,$shop["express_money"]);
		if($sendtype!="shop"){
			$total_money+=$express_money;
		}
		
		//返利金库支付
		$account_money=MM("flk","flk_account")->get($userid);
		if($account_money>=$total_money){
			$pay_money=0;
		}else{
			$pay_money=$total_money-$account_money;
		}
	 
		$pay_money=ceil($pay_money*100)/100;
		//打新
		$daxin=false;
		 
		if($shop["flk_new"]){
			$daxin=M("mod_flk_daxin")->selectRow(array(
				"where"=>" status=0 AND userid=".$userid." AND money>=".$goods_money,
				"order"=>"money ASC",
				"limit"=>1
			));
			$daxin=MM("flk","flk_daxin")->get($userid);
			if($daxin["money"]<$goods_money && $flkid){
				$daxin=false;
			}
		}
		$this->smarty->goAssign(array(
			"cartList"=>$cartList,
			"addrList"=>$addrList,
			"user_address_id"=>$user_address_id,
			"paytypeList"=>$paytypeList,
			"paytype"=>$paytype,
			"goods_money"=>$goods_money,
			"weight"=>$weight,
			"total_money"=>$total_money,
			"account_money"=>$account_money,
			"pay_money"=>$pay_money,
			"total_num"=>$total_num,
			"express_money"=>$express_money,
			"couponList"=>$couponList,
			"coupon_id"=>$coupon_id,
			"shop"=>$shop,
			"daxin"=>$daxin,
			"flk_discount"=>FLK_DISCOUNT,
			"sendtype"=>$sendtype
		));
		$this->smarty->display("flk_order/confirm.html");
	}
	public function onBuy(){
		
		$this->smarty->display("flk_order/buy.html");
	}
	public function onOrder(){
		$userid=M("login")->userid;
		$user_address_id=post("user_address_id","i");
		$shopid=get_post("shopid","i");
		$shop=M("mod_flk_shop")->selectRow(array(
			"where"=>"shopid=".$shopid,
			"fields"=>"shopid,flk_discount,flk_maxmoney,flk_new,status,express_money,send_startprice"
		));
		if($shop["status"]!=1){
			$this->goAll("商家不在营业中",1);
		}
		$sendtype=post("sendtype","h");
		$addr=M("user_address")->selectRow("id=".$user_address_id);
		if(empty($addr)){
			$this->goAll("请选择联系方式",1);
		}
		$cartids=post("cartid","i");
		if(empty($cartids)){
			$this->goAll("请选择商品",1);
		}
		
		$cartList=MM("flk","flk_cart")->Dselect(array(
			"where"=>" id in("._implode($cartids).")"
		));
		if(empty($cartList)){
			$this->goAll("购物车为空，请选择商品",1);
		}
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
			$goods_money+=$v["amount"]*$v["price"];
			$weight+=$v["amount"]*$v["weight"];
		}
		$total_money=$goods_money;
		if($total_money<$shop["send_startprice"]){
			$this->goAll("未达到起送价".$shop["send_startprice"]."元",1);
		}
		/* Start优惠券 */
		$coupon_id=post('coupon_id','i');
		if($coupon_id){
 
			$coupon=M("mod_flk_coupon")->selectRow("id=".$coupon_id);
			$etime=strtotime($coupon['end_time']);
			if(!$coupon || $goods_money<$coupon['lower_money'] || $etime<time()){
				//失效
			}else{
				$coupon_user=M("mod_flk_coupon_user")->selectRow("coupon_id=".$coupon_id." AND status=0 AND userid=".$userid);			
				if($coupon_user || $coupon["typeid"]==1){
					$coupon_money=$coupon['money'];
					if($coupon_user){
						M("mod_flk_coupon_user")->update(array("status"=>1),"id=".$coupon_user['id']);
					}				
					M("mod_flk_coupon")->update(array("use_num"=>$coupon['use_num']+1),"id=".$coupon_id);
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
		$express_money=0;
		
		if($sendtype!='shop'){
			$express_money=MM("flk","flk_express_fee")->getMoney($user_address_id,$weight,$shopid,$shop["express_money"]);		
			$total_money+=$express_money;
		}
		//生成订单
		$orderno="mflk".M("maxid")->get();
		$daySn=M("daysn")->get(array(
			"tablename"=>"flk"
		));
		$paytype=post("paytype","h");
		$shop_money=$total_money*(100-$shop["flk_discount"]-1)/100;
		//处理返利券
		$flkid=post("flkid","i");
		$flk_money=0;
		
		if($flkid){
			$flk_money=$total_money*FLK_DISCOUNT;
			$total_money+=$flk_money;
		}
		//返利金库支付
		$amoney=MM("flk","flk_account")->get($userid);
		$account_money=$total_money>=$amoney?$amoney:$total_money;
		$pay_money=$total_money>$amoney?($total_money-$account_money):0;
		//邀请插队
		$pin_orderid=0;
		if(get_post("ss_invite_orderid")){
			$ss_orderid=get_post("ss_invite_orderid","h");
		}else{
			$ss_orderid=isset($_SESSION["ss_invite_orderid"])?intval($_SESSION["ss_invite_orderid"]):0;
		}
		
		if($ss_orderid){
			$od=MM("flk","flk_order")->selectRow("orderid=".$ss_orderid);
			if($od && $od["shopid"]==$shopid){
				$pin_orderid=$ss_orderid;
			}
		}
		//生成订单
		$createtime=date("Y-m-d H:i:s");
		MM("flk","flk_order")->begin();
		if($account_money>0){
			MM("flk","flk_account")->addMoney(array(
				"userid"=>$userid,
				"money"=>-$account_money
			));
		}
		$ispay=0;
		if($pay_money==0){
			$ispay=1;
		}
		//打新券处理
		if($shop["flk_maxmoney"]<$goods_money){
			$this->goAll("订单金额大于单笔折扣限额，无法使用折扣券",1);
		}
		 if($shop["flk_new"]){
		 	$daxin=M("mod_flk_daxin")->get($userid);
			if($daxin["money"]<$goods_money && $flkid){
				$this->goAll("您暂未达到使用折扣返券资格",1);
			}
			$daxin=M("mod_flk_daxin")->addMoney(array(
				"userid"=>$userid,
				"money"=>-$goods_money
			));
			 
		 }
		$orderid=MM("flk","flk_order")->insert(array(
			"shopid"=>$shopid,
			"shop_money"=>$shop_money,
			"money"=>$total_money,
			"goods_money"=>$goods_money,
			"express_money"=>$express_money,
			"account_money"=>$account_money,
			"pay_money"=>$pay_money,
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
			"flkid"=>$flkid,
			"flk_money"=>$flk_money,
			"ispay"=>$ispay,
			"pin_orderid"=>$pin_orderid
		));
		
		//生成订单产品列表
		$prolist=array();
		foreach($cartList as $v){
			$prolist[]=$v;
			M("mod_flk_order_product")->insert(array(
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
		M("mod_flk_order_address")->insert(array(
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
		M("mod_flk_order_data")->insert(array(
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
				MM("flk","flk_product_ks")->changenum("total_num",-$v["amount"],"id=".$v["ksid"]);
			}else{
				MM("flk","flk_product")->changenum("total_num",-$v["amount"],"id=".$v["productid"]);
			}
		}
		//处理返利订单
		
		if($flkid){
			$ttm=$total_money-$flk_money;
			M("mod_flk_queue")->insert(array(
				"userid"=>$userid,
				"shopid"=>$shopid,
				"total_money"=>$ttm,
				"money"=>$ttm,
				"flk_money"=>$flk_money,
				"orderid"=>$orderid,
				"dateline"=>time()
			));
		}
		$buserid=0;
		
		//清除购物车
		MM("flk","flk_cart")->delete(" id in("._implode($cartids).")");
		MM("flk","flk_order")->commit();
		
		$action="pay";
		$rdata=array(
			"action"=>$action,
			"orderid"=>$orderid
		);
		if(!$ispay){
			$_GET["orderid"]=$orderid;
			$res=$this->onPay(1);
			$rdata['payurl']=$res['payurl'];
			$rdata['orderno']=$res['orderno'];
		}else{
			$rdata["action"]="finish";
			/**发送通知**/
			MM("flk","flk_shop_notice")->sendNewOrder($orderid);
		}
		
		$this->goAll("下单成功",0,$rdata);	
	}
	/***生成支付*****/
	public function onPay($return=0){
		$userid=M("login")->userid;
		$orderno="Re".M("maxid")->get();
		$orderid=get("orderid","i");
		$order=MM("flk","flk_order")->selectRow("orderid=".$orderid);
		if($order["ispay"]){
			$this->goAll("该订单已经支付",1);
		}
		//生成支付
		if($order["ordertype"]=="one"){
			$day=date("Y-m-d H:i:s");
			$product=M("mod_flk_product")->selectRow("id=".$order["productid"]." AND status=1 AND one_status=1 AND one_on=1 AND one_stime<'".$day."' AND one_etime>'".$day."' ");
			if(!$product){
				MM("flk","flk_order")->update(array("status"=>11),"orderid=".$orderid);
				$this->goALL("该商品秒杀活动已下线",1);
			}
		}
		
		$backurl=get_post("backurl","x");
		if($backurl==""){
			$backurl="/module.php?m=flk_order&a=success";
		}
		$pay_type=INWEIXIN?"wxpay":"alipay";
		$bmoney=$order["shop_money"]*0.1;
		$buserid=0;
		$orderdata=array(
			"table"=>"plugin",
			"callback"=>'
				MM("flk","flk_order")->pay(array(
					"ispay"=>1,
					"recharge_id"=>"$recharge_id",
					"paytype"=>"'.$paytype.'",
				),"orderid='.$orderid.'");
				MM("flk","flk_shop_notice")->sendNewOrder('.$orderid.');
			',
			"url"=>$backurl
		);
		$orderdata=base64_encode(json_encode($orderdata)); 
		$orderinfo=date("Y-m-d H:i:s")."购买商品";
		$order_product=date("Y-m-d H:i:s")."购买商品";
		$fromapp=get("fromapp");
		$money= $order['pay_money'];
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
		$this->smarty->display("flk_order/success.html");
	}
	/*
		单品订单
	*/
	public function onOrder_one(){
		 
		$id=get_post("id","i");
		$isCheck=post("isCheck","i");
		if(!$isCheck){
			$this->goAll("请先同意抢购协议",1);
		}
		$userid=M("login")->userid;
		$nickname=post("nickname","h");
		$telephone=post("telephone","h");
		if(empty($telephone)){
			$this->goAll("电话不能为空",1);
		}
		M("user_lastaddr")->add(array(
			"nickname"=>$nickname,
			"telephone"=>$telephone
		),$userid);
		$day=date("Y-m-d H:i:s");
		$product=M("mod_flk_product")->selectRow("id=".$id." AND status=1 AND one_status=1 AND one_on=1 AND one_stime<'".$day."' AND one_etime>'".$day."' ");
		if(!$product){
			$this->goALL("该商品秒杀活动已下线",1);
		}
		if($product["total_num"]==0){
			$this->goAll("库存不足",1);
		}
		$shopid=$product["shopid"];
		$shop=M("mod_flk_shop")->selectRow(array(
			"where"=>"shopid=".$shopid,
			"fields"=>"shopid,flk_discount,flk_maxmoney,flk_new,status,express_money,send_startprice"
		));
		$express_money=0;
		$discount_money=0;
		$coupon_money=0;
		$coupon_id=0;
		$goods_money=$total_money=$product["one_price"];
		$shop_money=$total_money*(100-$product["one_discount"])/100;
		//处理返利券
		$flkid=post("flkid","i");
		$flk_money=0;
		
		if($flkid){
			$flk_money=$total_money*FLK_DISCOUNT;
			$total_money+=$flk_money;
		} 
		//返利金库支付
		$amoney=MM("flk","flk_account")->get($userid);
		$account_money=$total_money>=$amoney?$amoney:$total_money;
		$pay_money=$total_money>$amoney?($total_money-$account_money):0;
		if($account_money>0){
			MM("flk","flk_account")->addMoney(array(
				"userid"=>$userid,
				"money"=>-$account_money
			));
		}
		$ispay=0;
		if($pay_money==0){
			$ispay=1;
		}
		//邀请插队
		$pin_orderid=0;
		if(get_post("ss_invite_orderid")){
			$ss_orderid=get_post("ss_invite_orderid","h");
		}else{
			$ss_orderid=isset($_SESSION["ss_invite_orderid"])?intval($_SESSION["ss_invite_orderid"]):0;
		}
		
		if($ss_orderid){
			$od=MM("flk","flk_order")->selectRow("orderid=".$ss_orderid);
			if($od && $od["shopid"]==$shopid){
				$pin_orderid=$ss_orderid;
			}
		}
		//生成订单
		$orderno="mflk".M("maxid")->get();
		$daySn=M("daysn")->get(array(
			"tablename"=>"flk"
		));
		$createtime=date("Y-m-d H:i:s");
		MM("flk","flk_order")->begin();
		$coupon_id=0;
		 
		$orderid=M("mod_flk_order")->insert(array(
			"shopid"=>$shopid,
			"shop_money"=>$shop_money,
			"money"=>$total_money,
			"goods_money"=>$goods_money,
			"express_money"=>$express_money,
			"account_money"=>$account_money,
			"pay_money"=>$pay_money,
			"coupon_id"=>$coupon_id,
			"coupon_money"=>$coupon_money,
			"total_num"=>1,
			"weight"=>1,
			"orderno"=>$orderno,
			"createtime"=>$createtime,
			"userid"=>$userid,
			"user_address_id"=>$user_address_id,
			"daySn"=>$daySn,
			"paytype"=>$paytype,
			"comment"=>$comment,
			"flkid"=>$flkid,
			"flk_money"=>$flk_money,
			"ispay"=>$ispay,
			"pin_orderid"=>$pin_orderid,
			"ordertype"=>"one",
			"sendtype"=>"shop",
			"productid"=>$product["id"]
		));
		M("mod_flk_order_product")->insert(array(
			"shopid"=>$shopid,
			"orderid"=>$orderid,
			"createtime"=>$createtime,
			"userid"=>$userid,
			"productid"=>$product["productid"],
			"ksid"=>0,
			"price"=>$product["one_price"],
			"amount"=>1
		));
		M("mod_flk_order_address")->insert(array(
			"shopid"=>$shopid,
			"orderid"=>$orderid,
			"createtime"=>$createtime,
			"userid"=>$userid,
			"truename"=>$nickname,
			"telephone"=>$telephone,
			"address"=>"",
			"province_id"=>0,
			"city_id"=>0,
			"town_id"=>0,
		));
		
		//生成订单 地址和商品
		$order_data=array(
			"address"=>array(
				"truename"=>$nickname,
				"telephone"=>$telephone,				 
				"address"=>""
			),
			"prolist"=>array(
				array(
					"id"=>$product["id"],
					"imgurl"=>images_site($product["imgurl"]),
					"price"=>$product["one_price"],
					"title"=>$product["title"],
					"ks_title"=>"",
					"ks_id"=>0,
					"amount"=>1
				)
			)
		);
		M("mod_flk_order_data")->insert(array(
			"shopid"=>$shopid,
			"orderid"=>$orderid,
			"userid"=>$userid,
			"createtime"=>date("Y-m-d H:i:s"),
			"updatetime"=>date("Y-m-d H:i:s"),
			"content"=>base64_encode(json_encode($order_data))
		));
		//减少库存
		MM("flk","flk_product")->changenum("total_num",-1,"id=".$product["id"]);
		//处理返利订单
		
		if($flkid){
			$ttm=$total_money-$flk_money;
			M("mod_flk_queue")->insert(array(
				"userid"=>$userid,
				"shopid"=>$shopid,
				"total_money"=>$ttm,
				"money"=>$ttm,
				"flk_money"=>$flk_money,
				"orderid"=>$orderid,
				"dateline"=>time(),
				"ordertype"=>"one",
				"productid"=>$product["id"]
			));
		}
		$buserid=0;
		MM("flk","flk_order")->commit();
		
		$action="pay";
		$rdata=array(
			"action"=>$action,
			"orderid"=>$orderid
		);
		if(!$ispay){
			$_GET["orderid"]=$orderid;
			$res=$this->onPay(1);
			$rdata['payurl']=$res['payurl'];
			$rdata['orderno']=$res['orderno'];
		}else{
			$rdata["action"]="finish";
			/**发送通知**/
			//处理返利
			MM("flk","flk_order")->pay(array(
				"ispay"=>1,
				"recharge_id"=>"",
				"paytype"=>"flk",
				)
				,"orderid=".$orderid
			);
			 
			MM("flk","flk_shop_notice")->sendNewOrder($orderid);
		}
		
		$this->goAll("下单成功",0,$rdata);	
		
		
	}
	public function onMy(){
		$userid=M("login")->userid;
		$where=" userid=".$userid;
		$url="/module.php?m=flk_order&a=my";
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
		$data=MM("flk","flk_order")->select($option,$rscount);
		if($data){
			foreach($data as $v){
				$oids[]=$v["orderid"];
				$shopids[]=$v["shopid"];
			}
			$sps=MM("flk","flk_shop")->getListByIds($shopids);
			$ods=MM("flk","flk_order_data")->getListByOrderIds($oids);
			foreach($data as $k=>$v){
				$v["shop"]=$sps[$v["shopid"]];
				$v['addr']=$ods[$v['orderid']]['address'];
				$v['prolist']=$ods[$v['orderid']]['prolist'];
				$v["status_name"]=MM("flk","flk_order")->getStatus($v);
				$v["timeago"]=timeago(strtotime($v["createtime"]));
				$data[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$data,
			"type"=>$type
		));
		$this->smarty->display("flk_order/my.html");
	}
	public function onShow(){
		$orderid=get("orderid","i");
		$order=MM("flk","flk_order")->selectRow("orderid=".$orderid);
		$orderdata=MM("flk","flk_order_data")->get($orderid);
		$shop=MM("flk","flk_shop")->get($order["shopid"],"shopid,imgurl,shopname,address,telephone"); 
		$order["status_name"]=MM("flk","flk_order")->getStatus($order);
		$order["timeago"]=timeago(strtotime($order["createtime"]));
		$ordercode="";
		$orderCodeEwm="";
		if($order["ispay"] && $order["status"]<3){
			$ordercode=MM("flk","flk_order_code")->get($orderid,$order["shopid"]);
			/*$c=json_encode(array(
				"action"=>"checkorder",
				"url"=>"../flk_order_code/index?ordercode=".$ordercode
			));
			*/
			$c=HTTP_HOST."/moduleshop.php?m=flk_order_code&action=checkorder&ordercode=".$ordercode;
			$orderCodeEwm=HTTP_HOST."/index.php?m=qrcode&content=".urlencode($c);
		}
		
		$this->smarty->goAssign(array(
			"order"=>$order,
			"shop"=>$shop,
			"addr"=>$orderdata["address"],
			"prolist"=>$orderdata["prolist"],
			"ordercode"=>$ordercode,
			"orderCodeEwm"=>$orderCodeEwm
		));
		$this->smarty->display("flk_order/show.html");
	}
	/**取消**/
	public function onCancel(){
		$orderid=get("orderid","i");
		$order=MM("flk","flk_order")->selectRow("orderid=".$orderid);
		$userid=M("login")->userid;
		if($order["userid"]!=$userid){
			$this->goAll("您无权限取消订单",1);
		}
		if($order["ordertype"]=='one'){
			$this->goAll("秒杀活动无法取消",1);
		}
		if($order["status"]!=0){
			$this->goAll("该订单已经处理了",1);
		}
		MM("flk","flk_order")->begin();
		MM("flk","flk_order")->update(array(
			"status"=>4
		),"orderid=".$orderid);
		if($order["ispay"]==1){
			//退款到原账户
			$recharge=M("recharge")->selectRow("id=".$order['recharge_id']);
			$odata=array(
				"tablename"=>"mod_flk_order",
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
				"content"=>"flk订单取消，申请退回支付渠道",
				"odata"=>base64_encode(json_encode($odata))
			));
		}
		//退还返券账户
		if($order["account_money"]){
			MM("flk","flk_account")->addMoney(array(
				"userid"=>$order["userid"],
				"money"=>$order["account_money"]
			));
		}
		//增加商品库存
		$proList=MM("flk","flk_order_product")->select(array(
			"where"=>"orderid=".$orderid
		));
		if($proList){
			foreach($proList as $v){
				if($v["ksid"]){
					MM("flk","flk_product_ks")->changenum("total_num",$v["amount"],"ksid=".$v["ksid"]);
				}else{
					MM("flk","flk_product")->changenum("total_num",$v["amount"],"id=".$v["productid"]);
				}	
			}
		}
		MM("flk","flk_order")->commit();
		MM("flk","flk_shop_notice")->sendUpdateOrder($orderid);
		$this->goAll("取消成功");
	}
	/**
	 * 收货
	 */
	
	public function onReceive(){
		$orderid=get("orderid","i");
		$order=MM("flk","flk_order")->selectRow("orderid=".$orderid);
		$shop=M("mod_flk_shop")->selectRow(array(
			"where"=>"shopid=".$order["shopid"],
			"fields"=>"shopid,flk_discount"
		));
		$userid=M("login")->userid;
		if($order["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		if($order["ispay"]==0){
			$this->goAll("该订单还未支付",1);
		}
		if($order["status"]>2){
			$this->goAll("该订单暂时无法收货",1);
		}
		MM("flk","flk_order")->begin();
		MM("flk","flk_order")->finish($order["orderid"]); 
		MM("flk","flk_order")->commit();
		MM("flk","flk_shop_notice")->sendUpdateOrder($orderid);
		$this->goAll("操作成功");
	}
	/**
	 * 评价
	 */
	public function onRaty(){
		$orderid=get("orderid","i");
		$order=MM("flk","flk_order")->selectRow("orderid=".$orderid);
		$userid=M("login")->userid;
		if($order["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		$sql="select a.id,a.raty_grade,a.productid,b.title,b.imgurl from ".table("mod_flk_order_product")." as a "
			." left join ".table("mod_flk_product")." as b "
			." on a.productid=b.id "
			." where a.orderid=".$orderid;
		$proList=MM("flk","flk_order")->getAll($sql);
		if($proList){
			foreach($proList as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$proList[$k]=$v;
			}
		}
		$raty=MM("flk","mod_flk_order_raty")->selectRow("orderid=".$orderid);
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
		$this->smarty->display("flk_order/raty.html");
	}
	
	public function onRatySave(){
		$userid=M("login")->userid;
		$orderid=get_post("orderid","i");
		$order=MM("flk","flk_order")->selectRow("orderid=".$orderid);
		if($order["ispay"]==0){
			$this->goAll("该订单还未支付",1);
		}
		if($order["status"]!=3){
			$this->goAll("该订单暂时无法评价",1);
		}
		if(!$order["israty"]){
			MM("flk","flk_order")->update(array(
				"israty"=>1
			),"orderid=".$orderid);
			$ratyData=M("mod_flk_order_raty")->postData();
			$ratyData["userid"]=$userid;
			$ratyData["createtime"]=date("Y-m-d H:i:s");
			$ratyData["shopid"]=$order["shopid"];
			M("mod_flk_order_raty")->insert($ratyData);
			$ratyPros=post("ratyPros","i");
			if(!empty($ratyPros)){
				foreach($ratyPros as $k=>$v){
					M("mod_flk_order_product")->update(array(
						"raty_grade"=>intval($v),
						"shopid"=>$order["shopid"]
					),"orderid=".$orderid." AND id=".intval($k));
				}
			}
		}
		MM("flk","flk_shop_notice")->sendUpdateOrder($orderid);
		$this->goAll("评价成功");
	}
	
}