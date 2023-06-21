<?php
class vipcard_rechargeControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		M("login")->checkLogin();
	}
	public function onDefault(){
		$list=M("mod_vipcard_option")->select(array(
			"where"=>" status=1 "
		));
		$paytypeList=pay_type_list(0);
		$this->smarty->goAssign(array(
			"list"=>$list,
			"paytypeList"=>$paytypeList
		));
		$this->smarty->display("vipcard_recharge/index.html");
	}
	
	public function onRecharge(){
		$userid=M("login")->userid;
		$opsid=get("opsid","i");
		
		$option=M("mod_vipcard_option")->selectRow("id=".$opsid." AND status=1");
		if(empty($option)){
			$this->goAll("请选择充值金额",1);
		}
		//生成支付
		$orderno="Re".M("maxid")->get();
		$backurl=get_post("backurl","x");
		if($backurl==""){
			$backurl="/module.php?m=b2c_order&a=success";
		}
		$pay_type=get("paytype","h");
		if($pay_type==''){
			$pay_type=INWEIXIN?"wxpay":"alipay";
		}
		
		$orderdata=array(
			"table"=>"plugin",
			"callback"=>'
				MM("vipcard","vipcard")->update(array(
					"userid"=>'.$userid.',
					 
					"money"=>"'.$option["money"].'",
				),"orderid='.$orderid.'");
			',
			"url"=>$backurl
		);
		$orderdata=base64_encode(json_encode($orderdata)); 
		$orderinfo=date("Y-m-d H:i:s")."购买商品";
		$order_product=date("Y-m-d H:i:s")."购买商品";
		$fromapp=get("fromapp");
		$money= $option['money'];
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
		if(!get("ajax")){
			header("Location: ".$url);
			exit;
		}else{
			$this->goALl("正在前往支付",0,$redata,$url);
		}
		
	}
	
}