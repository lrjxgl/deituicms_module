<?php
class f2c_orderControl extends skymvc{
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
		$cartid=get_post("cartid","i");
		$where=" userid=".$userid;
		if($cartid){
			$where.=" AND id=".$cartid;
		}
		$cartList=MM("f2c","f2c_cart")->Dselect(array(
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
			"express_money"=>$express_money
		));
		$this->smarty->display("f2c_order/confirm.html");
	}
	public function onBuy(){
		
		$this->smarty->display("f2c_order/buy.html");
	}
	public function onOrder(){
		$userid=M("login")->userid;
		$user_address_id=post("user_address_id","i");
		$addr=M("user_address")->selectRow("id=".$user_address_id);
		if(empty($addr)){
			$this->goAll("请选择地址",1);
		}
		$cartids=post("cartid","i");
		if(empty($cartids)){
			$this->goAll("请选择商品",1);
		}
		
		$cartList=MM("f2c","f2c_cart")->Dselect(array(
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
		$orderno="mf2c".M("maxid")->get();
		$daySn=M("daysn")->get(array(
			"tablename"=>"f2c"
		));
		$paytype=post("paytype","h");
		//生成订单
		$createtime=date("Y-m-d H:i:s");
		MM("f2c","f2c_order")->begin();
		$orderid=MM("f2c","f2c_order")->insert(array(
			"teamid"=>TEAMID,
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
			"comment"=>$comment
		));
		//生成订单产品列表
		$prolist=array();
		foreach($cartList as $v){
			$prolist[]=$v;
			M("mod_f2c_order_product")->insert(array(
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
		M("mod_f2c_order_address")->insert(array(
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
		M("mod_f2c_order_data")->insert(array(		 
			"orderid"=>$orderid,
			"userid"=>$userid,
			"createtime"=>date("Y-m-d H:i:s"),
			"updatetime"=>date("Y-m-d H:i:s"),
			"content"=>base64_encode(json_encode($order_data))
		));
		//减少库存
		foreach($cartList as $v){
			if($v["ksid"]){
				MM("f2c","f2c_product_ks")->changenum("total_num",-$v["amount"],"id=".$v["ksid"]);
			}else{
				MM("f2c","f2c_product")->changenum("total_num",-$v["amount"],"id=".$v["productid"]);
			}
		}	
		//清除购物车
		MM("f2c","f2c_cart")->delete(" id in("._implode($cartids).")");
		MM("f2c","f2c_order")->commit();
		
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
		}
		
		
		$this->goAll("下单成功",0,$rdata);	
	}
	/***生成支付*****/
	public function onPay($return=0){
		$userid=M("login")->userid;
		$orderno="Re".M("maxid")->get();
		$orderid=get("orderid","i");
		$order=MM("f2c","f2c_order")->selectRow("orderid=".$orderid);
		//生成支付
		
		$backurl=get_post("backurl","x");
		if($backurl==""){
			$backurl="/module.php?m=f2c_order&a=success";
		}
		$pay_type=INWEIXIN?"wxpay":"alipay";
		$orderdata=array(
			"table"=>"plugin",
			"callback"=>'
				MM("f2c","f2c_order")->update(array(
					"ispay"=>1,
					"recharge_id"=>"$recharge_id",
					"paytype"=>"'.$paytype.'",
				),"orderid='.$orderid.'");
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
		$this->smarty->display("f2c_order/success.html");
	}
	public function onMy(){
		$userid=M("login")->userid;
		$where=" userid=".$userid;
		$url="/module.php?m=f2c_order&a=my";
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
		$data=MM("f2c","f2c_order")->select($option,$rscount);
		if($data){
			foreach($data as $v){
				$oids[]=$v["orderid"];
			}
			$ods=MM("f2c","f2c_order_data")->getListByOrderIds($oids);
			foreach($data as $k=>$v){
				$v['addr']=$ods[$v['orderid']]['address'];
				$v['prolist']=$ods[$v['orderid']]['prolist'];
				$v["status_name"]=MM("f2c","f2c_order")->getStatus($v);
				$v["timeago"]=timeago(strtotime($v["createtime"]));
				$data[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$data,
			"type"=>$type
		));
		$this->smarty->display("f2c_order/my.html");
	}
	public function onShow(){
		$orderid=get("orderid","i");
		$order=MM("f2c","f2c_order")->selectRow("orderid=".$orderid);
		$orderdata=MM("f2c","f2c_order_data")->get($orderid);
		 
		$order["status_name"]=MM("f2c","f2c_order")->getStatus($order);
		$order["timeago"]=timeago(strtotime($order["createtime"]));
		$this->smarty->goAssign(array(
			"order"=>$order,
			"addr"=>$orderdata["address"],
			"prolist"=>$orderdata["prolist"]
		));
		$this->smarty->display("f2c_order/show.html");
	}
	/**取消**/
	public function onCancel(){
		$orderid=get("orderid","i");
		$order=MM("f2c","f2c_order")->selectRow("orderid=".$orderid);
		$userid=M("login")->userid;
		if($order["userid"]!=$userid){
			$this->goAll("您无权限取消订单",1);
		}
	 
		if($order["status"]!=0){
			$this->goAll("该订单已经处理了",1);
		}
		MM("f2c","f2c_order")->begin();
		MM("f2c","f2c_order")->update(array(
			"status"=>10
		),"orderid=".$orderid);
		if($order["ispay"]==1){
			//退款到原账户
			$recharge=M("recharge")->selectRow("id=".$order['recharge_id']);
			$odata=array(
				"tablename"=>"mod_f2c_order",
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
				"content"=>"f2c订单取消，申请退回支付渠道",
				"odata"=>base64_encode(json_encode($odata))
			));
		}
		//增加商品库存
		$proList=MM("f2c","f2c_order_product")->select(array(
			"where"=>"orderid=".$orderid
		));
		if($proList){
			foreach($proList as $v){
				if($v["ksid"]){
					MM("f2c","f2c_product_ks")->changenum("total_num",$v["amount"],"ksid=".$v["ksid"]);
				}else{
					MM("f2c","f2c_product")->changenum("total_num",$v["amount"],"id=".$v["productid"]);
				}	
			}
		}
		MM("f2c","f2c_order")->commit();
		$this->goAll("取消成功");
	}
	/**
	 * 收货
	 */
	
	public function onReceive(){
		$orderid=get("orderid","i");
		$order=MM("f2c","f2c_order")->selectRow("orderid=".$orderid);
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
		MM("f2c","f2c_order")->begin();
		$finishtime=date("Y-m-d H:i:s");
		MM("f2c","f2c_order")->update(array(
			"status"=>3,
			"isreceived"=>1,
			"finishtime"=>$finishtime
		),"orderid=".$orderid);
		//分配收入
		$money=$order["money"];
		$config=M("mod_f2c_config")->selectRow("1");
		
		if($config["shoptype"]==0 && $order["teamid"]){
			//代理开店模式 一级分销
			
			if($config["fctype"]==0){
				$balance=$money*$config["per_money"]/100;
			}else{
				$team=M("mod_f2c_team")->selectRow("teamid=".$order["teamid"]);
				//产品分成模式
				$sql="select p.id,p.per_money,op.price,op.amount,p.fanli from ".table("mod_f2c_order_product")." as op 
					left join ".table("mod_f2c_product")." as p 
					on op.productid=p.id
					where op.orderid=".$orderid."
				";
				$res=MM("f2c","f2c_product")->getAll($sql);
				$balance=0;
				$rankList=M("mod_f2c_config_rank")->select(array(
					"order"=>"min_grade ASC"
				));
				
				foreach($res as $rs){
					$discount=0;
					$fls=explode(",",$rs["fanli"]);
					//计算阶梯返利
					foreach($rankList as $k=>$v){
						$discount=$fls[$k];
						if($v["min_grade"]<=$team["team_money"] && $v["max_grade"]>$team["team_money"]){
							break;
						}
					}
					
					$balance+=$rs["price"]*$rs["amount"]*$discount/100;
				}
			}
			
			MM("f2c","f2c_team")->addMoney(array(
				"teamid"=>$order["teamid"],
				"money"=>$balance,
				"team_money"=>$order["money"],
				"createtime"=>$finishtime,
				"content"=>"订单完成获得了".$balance."元"
			));
			MM("f2c","f2c_done")->add(array(
				"teamid"=>$order["teamid"],
				"income"=>$balance, 
				"money"=>$money,
				"smonth"=>date("Ym",strtotime($finishtime)),
				"createtime"=>$finishtime,
			));
		}elseif($order["teamid"]){
			//邀请分销模式
			if($config["fctype"]==0){
				//订单分成模式
				MM("f2c","f2c_team")->fanli_order($order);
			}else{
				//产品分成模式
				MM("f2c","f2c_team")->fanli_product($order);
			}
			
		}
		//上级返利
		
		MM("f2c","f2c_order")->commit();
		$this->goAll("操作成功");
	}
	/**
	 * 评价
	 */
	public function onRaty(){
		$orderid=get("orderid","i");
		$order=MM("f2c","f2c_order")->selectRow("orderid=".$orderid);
		$userid=M("login")->userid;
		if($order["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		$sql="select a.id,a.raty_grade,a.productid,b.title,b.imgurl from ".table("mod_f2c_order_product")." as a "
			." left join ".table("mod_f2c_product")." as b "
			." on a.productid=b.id "
			." where a.orderid=".$orderid;
		$proList=MM("f2c","f2c_order")->getAll($sql);
		if($proList){
			foreach($proList as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$proList[$k]=$v;
			}
		}
		$raty=MM("f2c","mod_f2c_order_raty")->selectRow("orderid=".$orderid);
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
		$this->smarty->display("f2c_order/raty.html");
	}
	
	public function onRatySave(){
		$userid=M("login")->userid;
		$orderid=get_post("orderid","i");
		$order=MM("f2c","f2c_order")->selectRow("orderid=".$orderid);
		if($order["ispay"]==0){
			$this->goAll("该订单还未支付",1);
		}
		if($order["status"]!=3){
			$this->goAll("该订单暂时无法评价",1);
		}
		if(!$order["israty"]){
			MM("f2c","f2c_order")->update(array(
				"israty"=>1
			),"orderid=".$orderid);
			$ratyData=M("mod_f2c_order_raty")->postData();
			$ratyData["userid"]=$userid;
			$ratyData["createtime"]=date("Y-m-d H:i:s");
			M("mod_f2c_order_raty")->insert($ratyData);
			$ratyPros=post("ratyPros","i");
			if(!empty($ratyPros)){
				foreach($ratyPros as $k=>$v){
					M("mod_f2c_order_product")->update(array(
						"raty_grade"=>intval($v)
					),"orderid=".$orderid." AND id=".intval($k));
				}
			}
		}
		$this->goAll("评价成功");
	}
	
}