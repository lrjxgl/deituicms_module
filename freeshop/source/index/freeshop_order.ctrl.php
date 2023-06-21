<?php
class freeshop_orderControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		M("login")->checkLogin();
	}
	
	public function onDefault(){
		
	}
	public function onOrder(){
		M("login")->checkLogin();
		$productid=get_post('productid','i');
		$product=M("mod_freeshop_product")->selectRow("productid=".$productid);
		if(!$product){
			$this->goAll("数据出错",1);
		}
		if($product['status']!=1){
			$this->goAll("该活动已下线",1);
		}
		if($product["maxnum"]<=$product["buynum"]){
			$this->goAll("该商品已经被抢光啦",1);
		}
		if($product["ontime"]<time()){
			$this->goAll("该活动已经结束",1);
		}
		$userid=M("login")->userid;
		$row=M("mod_freeshop_order")->selectRow("userid=".$userid." AND productid=".$productid);
		if($row){
			$this->goAll("你已经参与过了",1);
		}
		$address=post('address','h');
		$nickname=post('nickname','h');
		$telephone=post('telephone','h');
		if(empty($address) || empty($nickname) || empty($telephone)){
			$this->goAll("请确认联系方式",1);
		}
		M("user_lastaddr")->add(array(
			"address"=>$address,
			"nickname"=>$nickname,
			"telephone"=>$telephone
			
		),$userid);
		 
		$products=$product["title"];
		$money=$product['price'];
		$user=M("user")->selectRow(array(
			"where"=>" userid=".$userid,
			"fields"=>" userid,nickname,money"
		));
		$ispay=0;
		$gopay=1;
		M("user")->begin();
		//增加销量
		M("mod_freeshop_product")->update(array(
			"buynum"=>$product["buynum"]+1
		),"productid=".$productid);
		if($user["money"]>=$money){
			$ispay=1;
			$gopay=0;
			M("user")->addMoney(array(
				"userid"=>$userid,
				"money"=>-$money,
				"content"=>"您购买了{$products},花了{$money}元"
			));	
				
		}
		$invite_fsuserid=get("share_userid","i");
		if(!$invite_fsuserid && isset($_SESSION["ss_share_userid"])){
			
			$invite_fsuserid=intval($_SESSION["ss_share_userid"]);
		}
		if($invite_fsuserid==$userid){
			$invite_fsuserid=0;
		} 
		$orderid=M("mod_freeshop_order")->insert(array(
			"userid"=>$userid,
			"productid"=>$productid,
			"money"=>$money,
			"nickname"=>$nickname,
			"address"=>$address,
			"telephone"=>$telephone, 
			"createtime"=>date("Y-m-d H:i:s"),
			"ispay"=>$ispay,
			"shopid"=>$product["shopid"],
			"sendtype"=>$product["sendtype"], 
			"invite_fsuserid"=>$invite_fsuserid,
			"etime"=>$product["etime"]
		));
		
		M("user")->commit();
		if($gopay==1){
			$_GET["orderid"]=$orderid;
			$payData=$this->onPay(true);
			$pdata=array(
				"orderid"=>$orderid,
				"gopay"=>$gopay,
				"payurl"=>$payData["payurl"],
				"action"=>"pay",
				"orderno"=>$payData["orderno"],
				"url"=>"/module.php?m=freeshop_order&a=pay&orderid=".$orderid
			);
		}else{
			$pdata=array(
				"orderid"=>$orderid,
				"gopay"=>$gopay,
				"url"=>"/module.php?m=freeshop_order&a=pay&orderid=".$orderid
			);
		}
		
		$this->goAll("下单成功",0,$pdata);
	}
	
	
	
	public function onPay($return=false){
		$orderid=get('orderid','i');
		$userid=M("login")->userid;
		$order=M("mod_freeshop_order")->selectRow("orderid=".$orderid);
		$productid=$order['productid'];
		if($order['ispay']){
			$this->goAll("已经支付过了",1);
		}
		$product=M("mod_freeshop_product")->selectRow("productid=".$productid);
		if($product['status']!=1){
			$this->goAll("该商品已下线",1);
		}
		if($product["maxnum"]==$product["buynum"]){
			$this->goAll("该商品已经被抢光啦",1);
		}
		if(ALIPAY!=1 && WXPAY!=1){
			$this->goAll("支付未配置无法进行支付操作",1);
		}
		if(INWEIXIN==true && WXPAY==1){
			$pay_type="wxpay";
		}else{
			$pay_type="alipay";
		}
		 
		$order_product=$product["title"];
		$orderno="re".M("maxid")->get();
		$backurl="/module.php?m=freeshop_order&a=success&productid=".$order['productid'];
		$orderdata=array(
			"table"=>"plugin",
			"callback"=>'
				M("mod_freeshop_order")->update(array(
					"ispay"=>1,
					"recharge_id"=>"$recharge_id",
					"paytype"=>"'.$pay_type.'",
				),"orderid='.$orderid.'");
						
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
			"orderinfo"=>$product['title'], 
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
		
		$this->smarty->display("freeshop_order/success.html");
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
			case "new":
				$where.=" status=0 AND ispay=1 ";
				 
				break;
			default:
				$where.=" AND status in(0,1,2,3,4) ";
				break;
		}
		$option=array(
			"where"=>$where,
			"order"=>"orderid DESC"
		);
		$data=MM("freeshop","freeshop_order")->select($option);
		$statuslist=MM("freeshop","freeshop_order")->statuslist();
		if($data){
			foreach($data as $v){
				$ids[]=$v['productid'];
				 
			}
			$products=MM("freeshop","freeshop_product")->getListByIds($ids);
			 
			foreach($data as $k=>$v){
				 
				$v['product']=$products[$v['productid']];
			 	$v['timeago']=timeago(strtotime($v['createtime']));
				$v['status_name']=MM("freeshop","freeshop_order")->getStatus($v);
				$data[$k]=$v;
			}
		}
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$this->smarty->goAssign(array(
			"list"=>$data,
			"per_page"=>$per_page,
			"statuslist"=>$statuslist,
			"type"=>$type
		));
		
		$this->smarty->display("freeshop_order/my.html");
	}
	public function onShow(){
		$orderid=get_post("orderid","i");
		$order=MM("freeshop","freeshop_order")->selectRow("orderid=".$orderid);
		$userid=M("login")->userid;
		if($order["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		$order['status_name']=MM("freeshop","freeshop_order")->getStatus($order);
		$product=MM("freeshop","freeshop_product")->selectRow("productid=".$order["productid"]);
		$product["imgurl"]=images_site($product["imgurl"]);
		$ordercode="";
		$orderCodeEwm="";
		if($order["ispay"] && $order["status"]<3){
			$ordercode=MM("freeshop","freeshop_order_code")->get($orderid,$order["shopid"]);
			$c=json_encode(array(
				"action"=>"url",
				"url"=>"../../pagefreeshop/freeshop_order_code/index?ordercode=".$ordercode
			));
			$orderCodeEwm=HTTP_HOST."/index.php?m=qrcode&content=".urlencode($c);
		}
		$shop=MM("freeshop","freeshop_shop")->get($order["shopid"]);
		if($order){
			$order["etime_format"]=date("m-d H:i",$order["etime"]);
		}
		$this->smarty->goAssign(array(
			"order"=>$order,
			"shop"=>$shop,
			"product"=>$product,
			"ordercode"=>$ordercode,
			"orderCodeEwm"=>$orderCodeEwm,
			"etime"=>$order["etime"]-time(),
		));
		$this->smarty->display("freeshop_order/show.html");
	}
	public function onReceive(){
		$userid=M("login")->userid;
		$orderid=get_post("orderid","i");
		$order=MM("freeshop","freeshop_order")->selectRow("orderid=".$orderid);
		if($order["ispay"]==0){
			$this->goAll("该订单还未支付",1);
		}
		if($order["status"]>2){
			$this->goAll("该订单已处理",1);
		}
		if($order["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		MM("freeshop","freeshop_order")->begin();
		$res=MM("freeshop","freeshop_order")->finish($orderid,$order);
		if($res["error"]){
			MM("freeshop","freeshop_order")->rollback();
			$this->goAll($res["message"],1);	
		}
		MM("freeshop","freeshop_order")->commit();
		//处理邀请用户赏金
		
	
		$this->goAll("订单完成");
	}
	public function onRaty(){
		$orderid=get_post("orderid","i");
		$order=MM("freeshop","freeshop_order")->selectRow("orderid=".$orderid);
		$product=MM("freeshop","freeshop_product")->selectRow("productid=".$order["productid"]);
		$product["imgurl"]=images_site($product["imgurl"]);
		$raty=M("mod_freeshop_order_raty")->selectRow("orderid=".$orderid);
		$this->smarty->goAssign(array(
			"order"=>$order,
			"product"=>$product,
			"raty"=>$raty
		));
		$this->smarty->display("freeshop_order/raty.html");
	}
	public function onRatySave(){
		$userid=M("login")->userid;
		$orderid=get_post("orderid","i");
		$order=MM("freeshop","freeshop_order")->selectRow("orderid=".$orderid);
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
			MM("freeshop","freeshop_order")->update(array(
				"israty"=>1
			),"orderid=".$orderid);
			$ratyData=M("mod_freeshop_order_raty")->postData();
			$ratyData["userid"]=$userid;
			$ratyData["createtime"]=date("Y-m-d H:i:s");
			$ratyData["productid"]=$order["productid"];
			$raty=M("mod_freeshop_order_raty")->selectRow("orderid=".$orderid);
			if($raty){
				M("mod_freeshop_order_raty")->update($ratyData,"orderid=".$orderid);
			}else{
				M("mod_freeshop_order_raty")->insert($ratyData);
			}
			
			 
		}
		$this->goAll("评价成功");
	}
}
?>