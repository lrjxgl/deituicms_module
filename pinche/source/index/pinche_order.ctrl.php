<?php
class pinche_orderControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		M("login")->checkLogin();
	}
	public function onDefault(){
		
	}
	
	public function onMy(){
		$userid=M("login")->userid;
		$where=" userid=".$userid;
		$type=get("type");
		switch($type){
			case "new":
				$where.=" AND status in(0,1) ";
				break;
			case "unpay":
				$where.=" AND ispay=0 AND status in(0,1,2,3) ";
				break;
			 
			case "finish":
				$where.=" AND status=3 ";
				break;
			case "cancel":
				$where.=" AND status=4 ";	
				break;
			default:
				$where.=" AND status in(0,1,2,3) ";
				break;
			
		}
		$limit=12;
		$start=get("per_page","i");
		$ops=array(
			"where"=>$where,
			"limit"=>$limit,
			"start"=>$start,
			"order"=>"orderid DESC"		
		);
		$list=MM("pinche","pinche_order")->Dselect($ops);
		if($list){
			foreach($list as $k=>$v){
				$v["timeago"]=timeago($v["dateline"]);
				$v["wait_etime_fmt"]=date("H:i",$v["wait_etime"]);
				$list[$k]=$v;
			}
		}
		$this->smarty->goassign(array(
			"list"=>$list
		));
		$this->smarty->display("pinche_order/my.html");
	}
	
	public function onShow(){
		$orderid=get("orderid","i");
		$order=MM("pinche","pinche_order")->selectRow("orderid=".$orderid);
		$line=M("mod_pinche_line")->selectRow("lineid=".$order["lineid"]);		
		if($order["driverid"]){
			$driver=M("mod_pinche_driver")->selectRow("driverid=".$order["driverid"]);
			
		}
		$eaddr=false;
		if($order["end_addrid"]){
			$eaddr= M("mod_pinche_line_addr")->selectRow("addrid=".$order["end_addrid"]);
		}
		
		$statusList=MM("pinche","pinche_order")->statusList();
		$order["status_name"]=$statusList[$order["status"]];
		$order["wait_etime_fmt"]=date("H:i",$order["wait_etime"]);
		$order["createtime"]=date("Y-m-d H:i:s",$order["dateline"]);
		$this->smarty->goAssign(array(
			"order"=>$order,
			"driver"=>$driver,
			"line"=>$line,
			"eaddr"=>$eaddr
		));
		$this->smarty->display("pinche_order/show.html");
	}
	public function onGetMoney(){
		$userid=M("login")->userid;
		$lineid=get_post("lineid","i");
		$start_addrid=get("start_addrid","i");
		$start_addr=get_post("start_addr","h");
		$start_lat=get_post("start_lat","f");
		$start_lng=get_post("start_lng","f");
		$end_addrid=get_post("end_addrid","i");
		$totalmoney=get_post("totalmoney","i");
		$usernum=get_post("usernum","i");
		$line=M("mod_pinche_line")->selectRow("lineid='".$lineid."'");
		//处理不同时段的价格
		$baiTime=MM("pinche","pinche_line")->getBaiTime();
		if($baiTime){
			$line["basemoney"]=$line["bai_money"]; 
		}else{
			$line["basemoney"]=$line["hei_money"];
		}
		if(empty($line)){
			$this->goAll("线路出错",1);
		}
		/*
			两种计费方式
			1.按位置计费
			2.按人头计费
		*/
		$money=$line["basemoney"]*$usernum;
		if($start_addrid){
			
			$addr=M("mod_pinche_line_addr")->selectRow("addrid=".$start_addrid);
			if($line["send_paytype"]==0){
				$money+=$addr["price"]*$usernum;
			}else{
				$money+=$addr["price"];
			} 
			
		}else{
			
			if($start_addr){
				$dm= ceil($line["sendmoney"]*distanceByLnglat($start_lng,$start_lat,$line["start_lng"],$line["start_lat"])/1000);
				
				$money+=$dm;
			}
		}
		
		if($end_addrid){
			$addr=M("mod_pinche_line_addr")->selectRow("addrid=".$end_addrid);
			if($addr){
				
				//$money+= ceil($line["sendmoney"]*distanceByLnglat($addr["lng"],$addr["lat"],$line["end_lng"],$line["end_lat"])/1000);
				if($line["send_paytype"]==0){
					$money+=$addr["price"]*$usernum;
				}else{
					$money+=$addr["price"];
				}
			}else{
				$this->goAll("暂不支持下车点",1);
			}
		}
		
		$this->goAll("success",0,array(
			"total_money"=>$money
		));
	}
	public function onOrder(){
		$userid=M("login")->userid;
		$lineid=post("lineid","i");
		$start_addr=post("start_addr","h");
		$start_lat=post("start_lat","f");
		$start_lng=post("start_lng","f");
		$end_addrid=post("end_addrid","i");
		$totalmoney=post("totalmoney","i");
		$ppid=post("ppid","i");
		$usernum=post("usernum","i");
		$line=MM("pinche","pinche_line")->selectRow("lineid='".$lineid."'");
		//处理不同时段的价格
		$baiTime=MM("pinche","pinche_line")->getBaiTime();
		if($baiTime){
			$line["basemoney"]=$line["bai_money"];
		}else{
			$line["basemoney"]=$line["hei_money"];
		}
		
		$indata=array(
			"userid"=>$userid,
			"dateline"=>time(),
			"usernum"=>$usernum,
			"lineid"=>$lineid
		);
		if(empty($line)){
			$this->goAll("线路出错",1);
		}
		$people=M("mod_pinche_people")->selectRow("ppid=".$ppid." AND status=1");
		if(empty($people)){
			$this->goAll("请选择乘客",1);
		}
		$indata["nickname"]=$people["nickname"];
		$indata["telephone"]=$people["telephone"];
		//处理计费
		$money=$line["basemoney"]*$usernum;
		$start_addrid=post("start_addrid","i");
		if($start_addrid){
			
			$addr=M("mod_pinche_line_addr")->selectRow("addrid=".$start_addrid);
			if($line["send_paytype"]==0){
				$money+=$addr["price"]*$usernum;
			}else{
				$money+=$addr["price"];
			} 
			$start_lat=$addr["lat"];
			$start_lng=$addr["lng"];
			$start_addr=$addr["addr"];
		}else{
			if($start_addr){
				$dm= ceil($line["sendmoney"]*distanceByLnglat($start_lng,$start_lat,$line["start_lng"],$line["start_lat"])/1000);
				$money+=$dm;
			}
		}
		$indata["start_lat"]=$start_lat;
		$indata["start_lng"]=$start_lng;
		$indata["start_addr"]=$start_addr;
		//终点站
		$indata["end_addr"]=$line["end_addr"];
		if($end_addrid){
			$addr=M("mod_pinche_line_addr")->selectRow("addrid=".$end_addrid);
			if($addr){
				if($line["send_paytype"]==0){
					$money+=$addr["price"]*$usernum;
				}else{
					$money+=$addr["price"];
				}
				$indata["end_addrid"]=$end_addrid;
				
			}else{
				$this->goAll("暂不支持下车点",1);
			}
		}
		$indata["money"]=$money;
		$config=M("mod_pinche_config")->selectRow("1");
		$indata["retype"]=$config["retype"];
		$action="pay";
		$orderid=M("mod_pinche_order")->insert($indata);
		if($config["retype"]==0){
		 
			$pdata=array(
				"orderid"=>$orderid,
				"action"=>"finish"
			);
			MM("pinche","pinche_group")->joinGroup($orderid,0);
		}elseif($user["money"]>=$money){
			M("user")->addMoney(array(
				"userid"=>$userid,
				"money"=>-$order["money"],
				"content"=>"支付拼车费用花了".$order["money"]."元"
			));
			MM("pinche","pinche_order")->update(
				array("ispay"=>1),
				"orderid=".$orderid
			);
			MM("pinche","pinche_group")->joinGroup($orderid);
			 
			$pdata=array(
				"orderid"=>$orderid,
				"action"=>"finish"
			);
		}else{
			$_GET["orderid"]=$orderid;
			$payData=$this->onPay(true);
			$pdata=array(
				"orderid"=>$orderid,
				"payurl"=>$payData["payurl"],
				"action"=>$payData["action"],
				"orderno"=>$payData["orderno"],
				"url"=>"/module.php?m=fxa_order&a=pay&orderid=".$orderid
			);
		}
		
		$this->goAll("下单成功".$money,0,$pdata);
	}
	
	public function onPay($return=false){
		$orderid=get('orderid','i');
		$userid=M("login")->userid;
		//余额支付
		$user=M("user")->getUser($userid,"userid,money");
		
		$order=M("mod_pinche_order")->selectRow("orderid=".$orderid);
		if(empty($order)){
			$this->goAll("订单不存在",1);
		}
		if($order['ispay']){
			$this->goAll("已经支付过了",1);
		}
		$action="pay";
		if($user["money"]>=$order["money"]){
			M("user")->addMoney(array(
				"userid"=>$userid,
				"money"=>-$order["money"],
				"content"=>"支付拼车费用花了".$order["money"]."元"
			));
			MM("pinche","pinche_order")->update(
				array("ispay"=>1),
				"orderid=".$orderid
			);
			MM("pinche","pinche_group")->joinGroup($orderid);
			$action="finish";
			$redata=array(
				"action"=>$action,
			);
			if($return){
				return $redata;
			}
			//end 固定支付
			 
			$this->goAll("支付成功",0,$redata);
		} 
		if(ALIPAY!=1 && WXPAY!=1){
			$this->goAll("支付未配置无法进行支付操作",1);
		}
		if(INWEIXIN==true && WXPAY==1){
			$pay_type="wxpay";
		}else{
			$pay_type="alipay";
		}
		 
		$order_product="付车费";
		$orderno="re".M("maxid")->get();
		$backurl="/module.php?m=pinche";
		$orderdata=array(
			"table"=>"plugin",
			"callback"=>'
				MM("pinche","pinche_order")->update(
					array(
						"ispay"=>1,
						"recharge_id"=>"$recharge_id",
					),"orderid='.$orderid.'");
				MM("pinche","pinche_group")->joinGroup('.$orderid.');			
			',
			"url"=>$backurl
		);
		$orderdata=base64_encode(json_encode($orderdata));
		$money=$order['money'];
		/*****插入充值表******/
		M("recharge")->insert(array(
			"userid"=>$userid,
			"money"=>$money,
			"pay_type"=>$pay_type,
			"orderno"=>$orderno,
			"orderinfo"=>$order_product, 
			"type_id"=>1,
			"tablename"=>"",
			"dateline"=>time(),
			"status"=>2,
			
			"orderdata"=>$orderdata,
		));
		
		/*插入充值表结束*/
		
		$bank_type="";
		$order_info=$order_product;
		$url=HTTP_HOST."/index.php?m=recharge_{$pay_type}&a=go";
		$url.="&orderno=$orderno";
		$url.="&bank_type=".$bank_type;
		$url.="&order_product=".urlencode($order_product);
		$url.="&order_price=".$money;
		$url.="&order_info=".urlencode($order_info);
		$url.="&backurl=".urlencode($backurl);
		$redata=array(
			"payurl"=>$url,
			"action"=>$action,
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
	
	public function onFinish(){
		$userid=M("login")->userid;
		$driver=M("mod_pinche_driver")->selectRow("userid=".$userid);
		$orderid=get("orderid","i");
		$order=MM("pinche","pinche_order")->selectRow("orderid=".$orderid);
		if($order["userid"]!=$userid && $order["driverid"]!=$driver["driverid"]){
			$this->goAll("暂无权限",1);
		}
		if(!$order["ispay"]){
			$this->goAll("该订单暂未支付",1);
		}
		if($order["status"]>2){
			$this->goAll("该订单已结算",1);
		}
		if($order["gid"]==0){
			$this->goAll("该订单暂未成团",1);
		}
		MM("pinche","pinche_order")->begin();
		MM("pinche","pinche_order")->update(array(
			"status"=>3
		),"orderid=".$orderid);
		$config=M("mod_pinche_config")->selectRow("1");
		if($order["invite_userid"]){
			$pimoney=$order["money"]*$config["invite_per_money"]/100;
			M("user")->addMoney(array(
				"userid"=>$order["invite_userid"],
				"money"=>$pimoney,
				"content"=>"你邀请好友拼车获得了".$pimoney."元"
			));
		}
		$amoney=$order["money"]*$config["site_per_money"]/100;
		MM("pinche","pinche_driver_account")->addMoney(array(
			"money"=>$amoney,
			"driverid"=>$order["driverid"],
			"content"=>"乘客订单完成，获得了{$amoney}元"
		));
		MM("pinche","pinche_order")->commit();
		$this->goAll("订单结算成功");
	}
	
	public function onCancel(){
		$userid=M("login")->userid;
		$orderid=get("orderid","i");
		$order=MM("pinche","pinche_order")->selectRow("orderid=".$orderid);
		if($order["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		$group=MM("pinche","pinche_group")->selectRow("gid=".$order["gid"]);
		MM("pinche","pinche_order")->begin();
		MM("pinche","pinche_order")->update(array(
			"status"=>4
		),"orderid=".$orderid);
		//处理团
		MM("pinche","pinche_group")->update(array(
			"usernum"=>$group["usernum"]-$order["usernum"],
			"freenum"=>$group["freenum"]+$order["usernum"]
		),"gid=".$order["gid"]);
		//处理退款
		MM("pinche","pinche_order")->cancel($orderid,$order);
		MM("pinche","pinche_order")->commit();
		$this->goAll("订单取消成功");
		
	}
	
}