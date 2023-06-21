<?php
class zhuli_orderControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onInit(){
		M("login")->checkLogin();
	}
	public function onDefault(){
		
		$this->smarty->display("zhuli_order/index.html");
	}
	
	public function onConfirm(){
		$userid=M("login")->userid;
		$joinid=get('joinid','i');
		$join=MM("zhuli","mod_zhuli_join")->selectRow("id=".$joinid);
		if($join['userid']!=$userid){
			$this->goAll("你无权限",1);
		}
		//判断是否已下单
		if($join['isfinish']){
			$this->goAll("已经购买过了",1);
		}
		$data=MM("zhuli","mod_zhuli")->selectRow("id=".$join['zlid']);
		$buy_money=$data['market_price']-$join['zlmoney']*$data['zlmoney']*0.01;
		$addr=M("user_lastaddr")->get($userid);
		$this->smarty->goAssign(array(
			"join"=>$join,
			"zhuli"=>$data,
			"buy_money"=>$buy_money,
			"addr"=>$addr
		));
		$this->smarty->display("zhuli_order/confirm.html");
	}
	
	public function onOrder(){
		$userid=M("login")->userid;
		$address=post('address','h');
		$nickname=post('nickname','h');
		$telephone=post('telephone','h');
		$comm=post('comm','h');
		if(empty($address) || empty($nickname) || empty($telephone)){
			$this->goAll("请确认联系方式",1);
		}
		if(!is_tel($telephone)){
			$this->goAll("电话号码不正确",1);
		}
		M("user_lastaddr")->add(array(
			"address"=>$address,
			"nickname"=>$nickname,
			"telephone"=>$telephone
			
		),$userid);
		$joinid=get_post('joinid','i');
		$join=MM("zhuli","mod_zhuli_join")->selectRow("id=".$joinid);
		if($join['userid']!=$userid){
			$this->goAll("你无权限",1);
		}
		//判断是否已下单
		if($join['isfinish']){
			$this->goAll("已经购买过了",1);
		}
		$data=MM("zhuli","mod_zhuli")->selectRow("id=".$join['zlid']);
		if($data['endtime']<time()){
			$this->goAll("活动时间已经结束了",1);
		}
		$buy_money=$data['market_price']-$join['zlmoney']*$data['zlmoney']*0.01;
		$orderid=M("mod_zhuli_order")->insert(array(
			"userid"=>$userid,
			"money"=>$buy_money,
			"zlid"=>$join['zlid'],
			"createtime"=>date("Y-m-d H:i:s"),
			"address"=>$address,
			"telephone"=>$telephone,
			"nickname"=>$nickname,
			"comm"=>$comm
		));
		M("mod_zhuli_join")->update(array(
			"isfinish"=>1
		),"id=".$joinid);
		$url="/module.php?m=zhuli_order&a=pay&orderid=".$orderid;
		$this->goAll("下单成功",0,$orderid,$url);
	}
	
	public function onPay(){
		$orderid=get('orderid','i');
		$userid=M("login")->userid;
		$order=M("mod_zhuli_order")->selectRow("orderid=".$orderid);
		 
		if($order['ispay']){
			$this->goAll("已经支付过了",1,0,"/module.php?m=zhuli_list");
		}
		$zhuli=M("mod_zhuli")->selectRow("id=".$order['zlid']);
		if($zhuli['endtime']<time()){
			$this->goAll("活动已结束，订单无法进行支付",1,0,"/module.php?m=zhuli_list");
		}
		 
		$user=M("login")->getUser();
		if(1==2 && $user['money']>=$order['moeny']){
			 
			M("user")->addMoney(array(
				"userid"=>$userid,
				"money"=>-$order['moeny'],
				"content"=>"您参与了《".$zhuli['title']."》，花了".$order['moeny']."元，"
			));
			M("mod_zhuli_order")->update(array("ispay"=>1),"orderid=".$orderid);
			M("mod_zhuli")->update(array("buy_num"=>$zhuli['buy_num']+1),"id=".$zhuli['id']);
			$this->goAll("余额支付成功",0,0,"/module.php?m=zhuli_list");
		} 
		
		if(ALIPAY!=1 && WXPAY!=1){
			$this->goAll("支付未配置无法进行支付操作",1,0,"/module.php?m=zhuli_list");
		}
		if(INWEIXIN==true){
			$pay_type="wxpay";
		}else{
			$pay_type="alipay";
		}
		 
		$order_product=$zhuli["title"];
		$orderno="re".date("YmdHis").M("login")->userid;
		$backurl="/module.php?m=zhuli&id=".$zhuli['id'];
		$orderdata=array(
			"table"=>"plugin",
			"callback"=>'
				M("mod_zhuli_order")->update(array("ispay"=>1),"orderid='.$orderid.'");
				M("mod_zhuli")->update(array("buy_num"=>'.($zhuli['buy_num']+1).'),"id='.$zhuli['id'].'");',
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
			"orderinfo"=>"助力抢单订单", 
			"type_id"=>1,
			"tablename"=>"",
			"dateline"=>time(),
			"status"=>2,
			"siteid"=>SITEID,
			"orderdata"=>$orderdata,
		));
		
		/*插入充值表结束*/
		
		$url=HTTP_HOST."/index.php?m=recharge_{$pay_type}&a=go";
		$url.="?orderno=$orderno";
		$url.="&bank_type=".$bank_type;
		$url.="&order_product=".urlencode($order_product);
		$url.="&order_price=".$order_price;
		$url.="&order_info=".urlencode($order_info);
		$url.="&backurl=".base64_encode($backurl);
		header("Location: ".$url);
		exit;
	}
	public function onMy(){
		M("login")->checkLogin();		
		$userid=M("login")->userid;
		$where=" userid=".$userid;
		$status=get('status','i');
		if($status){
			$where.=" AND status=$status";
		}
		$orderlist=M("mod_zhuli_order")->select(array(
			"where"=>$where,
			"order"=>"orderid DESC"
		));
		 
		$status_list=array(
			0=>"未确认",
			1=>"已确认",
			2=>"已发货",
			3=>"已完成",
			8=>"已取消"
		); 
		if($orderlist){
			foreach($orderlist as $v){
				$pids[]=$v['zlid'];
			}
			$ps=MM("zhuli","zhuli")->getListByIds($pids);
		 
			foreach($orderlist as &$v){
				 
				$v['title']=$ps[$v['zlid']]['title'];
				$v['imgurl']=images_site($ps[$v['zlid']]['imgurl']);
			 
				$v['price']=$ps[$v['zlid']]['price'];
				$v['status_name']=$status_list[$v['status']];
			}
		}
		
		$this->smarty->goASsign(array(
			"orderlist"=>$orderlist,
			"statuslist"=>$status_list
		));
		$this->smarty->display("zhuli_order/my.html");
		
	}
	
	public function onReceive(){
		$orderid=get("orderid","i");
		$userid=M("login")->userid;
		$order=M("mod_zhuli_order")->selectRow("orderid=".$orderid);
		if($order['isreceived']!=0){
			$this->goAll("该订单已经接收了",1);
		}
		M("mod_zhuli_order")->update(array(
			"isreceived"=>1
		),"orderid=".$orderid);
		$this->goAll("接收成功");
	} 
	
}
?>