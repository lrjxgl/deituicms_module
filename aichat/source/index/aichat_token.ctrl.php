<?php
class aichat_tokenControl extends skymvc{
	public function onDefault(){
		
	}
	
	public function onNewUser(){
		//新用户领取token
		M("login")->checkLogin();
		$userid=M("login")->userid;
		//判定是否新用户
		$row=M("mod_aichat_onetask")->selectRow("userid=".$userid." AND taskname='new_user_token'");
		if(!empty($row)){
			$this->goAll("你已经领取过了",1);
		}
		M("mod_aichat_onetask")->begin();
		M("mod_aichat_onetask")->insert(array(
			"userid"=>$userid,
			"taskname"=>"new_user_token",
			"createtime"=>date("Y-m-d H:i:s")
		));
		$cfg=MM("aichat","aichat_config")->get();
		$num=$cfg["new_user_token"];
		MM("aichat","aichat_user")->addToken(array(
			"userid"=>$userid,
			"num"=>$num,
			"content"=>"新用户赠送".$num."个Token"
		));
		M("mod_aichat_onetask")->commit();
		$this->goAll("领取成功");
	}
	
	public function onBuy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$list=M("mod_aichat_token")->select(array(
			"where"=>" status=1 ",
			"order"=>" orderindex ASC"
		));
		$aiuser=MM("aichat","aichat_user")->get($userid);
		$this->smarty->goAssign(array(
			"list"=>$list,
			"aiuser"=>$aiuser
		));
		$this->smarty->display("aichat_token/buy.html");
	}
	public function onSave(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$tokenid=post("tokenid","i");
		$row=M("mod_aichat_token")->selectRow("tokenid=".$tokenid);
		if(empty($row) || $row["status"]!=1){
			$this->goAll("已下线",1);
		}
		//
		$ispay=0;
		
		M("mod_aichat_token_order")->insert(array(
			"userid"=>$userid,
			"tokenid"=>$row["tokenid"],
			"num"=>$row["num"],
			"money"=>$row["price"],
			"createtime"=>date("Y-m-d H:i:s"),
			"ispay"=>$ispay
		));
		//在线支付
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
			$rdata=array(
				"action"=>"success",
				"orderid"=>$orderid
			);
		}	
		
		$this->goAll("购买成功",0,$rdata);
	}
	
	/***生成支付*****/
	public function onPay($return=0){
		$userid=M("login")->userid;
		$orderno="Re".M("maxid")->get();
		$orderid=get("orderid","i");
		$order=MM("aichat","aichat_token_order")->selectRow("orderid=".$orderid);
		//生成支付
		
		$backurl=get_post("backurl","x");
		if($backurl==""){
			$backurl="/module.php?m=aichat_token_order&a=success&orderid=".$orderid; 
		}
		$pay_type=INWEIXIN?"wxpay":"alipay";
		$orderdata=array(
			"table"=>"plugin",
			"callback"=>'
				MM("aichat","aichat_token_order")->buySuccess(array(
					"ispay"=>1,
					"recharge_id"=>"$recharge_id",
					"paytype"=>"'.$paytype.'",
					"orderid='.$orderid.'"
				));
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
	
	 
	
}
