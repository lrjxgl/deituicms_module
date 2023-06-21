<?php
class household_order_appendControl extends skymvc{
	public function onDefault(){
		
	}
	public function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$start=get("per_page","i");
		$limit=12;
		$where=" userid=".$userid;
		$rscount=true;
		$ops=array(
			"where"=>$where,
			"start"=>$start,
			"limit"=>$limit,
			"order"=>"ispay ASC,id DESC"
		);
		$list=M("mod_household_order_append")->select($ops,$rscount);
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$this->smarty->goAssign(array(
			"list"=>$list,
			"rscount"=>$rscount,
			"per_page"=>$per_page
		));
		$this->smarty->display("household_order_append/my.html");
	}
	public function onPay($return=0){
		$userid=M("login")->userid;
		$orderno="Re".M("maxid")->get();
		$orderid=get("orderid","i");
		$order=MM("household","household_order_append")->selectRow("orderid=".$orderid);
		//生成支付
		if($order["ispay"]){
			$this->goAll("订单已经支付了",1);
		}
		$backurl=get_post("backurl","x");
		if($backurl==""){
			$backurl="/module.php?m=household_order&a=success&orderid=".$orderid; 
		}
		$pay_type=INWEIXIN?"wxpay":"alipay";
		$orderdata=array(
			"table"=>"plugin",
			"callback"=>'
				MM("household","household_order_append")->update(array(
					"ispay"=>1,
					"recharge_id"=>"$recharge_id"
				 
				),"orderid='.$orderid.'");
				
			',
			"url"=>$backurl
		);
		$orderdata=base64_encode(json_encode($orderdata)); 
		$orderinfo=date("Y-m-d H:i:s")."支付家政服务尾款";
		$order_product=date("Y-m-d H:i:s")."支付家政服务尾款";
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
}
?>