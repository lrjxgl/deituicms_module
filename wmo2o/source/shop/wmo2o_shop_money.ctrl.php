<?php
class wmo2o_shop_moneyControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$shop=M("mod_wmo2o_shop")->selectRow("shopid=".SHOPID);
		$shopmoney=MM("wmo2o","wmo2o_shop_money")->get(SHOPID);
		$list=M("mod_wmo2o_shop_money_log")->select(array(
			"where"=>"shopid=".SHOPID,
			"order"=>"id DESC",
			"limit"=>48
		)); 
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"shopmoney"=>$shopmoney,
			"list"=>$list
		));
		$this->smarty->display("wmo2o_shop_money/index.html");
	}
	
	public function onRecharge(){
		
		$pay_type_list= pay_type_list(0,array("unpay"=>1));
		$this->smarty->goAssign(array(
			"pay_type_list"=>$pay_type_list
		));
		$this->smarty->display("wmo2o_shop_money/recharge.html");
	}
	
	public function onRechargeSave(){
		if(ALIPAY!=1 && WXPAY!=1){
			$this->goAll("支付未配置无法进行支付操作",1);
		}
		if(INWEIXIN==true && WXPAY==1){
			$pay_type="wxpay";
		}else{
			$pay_type="alipay";
		}
		if(post("pay_type")){
			$pay_type=post("pay_type","h");
		} 
		$order_info=$order_product="商家在线充值";
		$orderno="re".M("maxid")->get();
		$backurl=get_post("backurl","x");
		if(!$backurl){
			$backurl="/moduleshop.php?m=wmo2o";
		}
		$money=post("money","i");
		if($money<=0){
			$this->goAll("金额必须大于0",1);
		}
		$orderdata=array(
			"table"=>"plugin",
			"callback"=>'
				MM("wmo2o","wmo2o_shop_money")->addMoney(array(
					"shopid"=>'.SHOPID.',
					"balance"=>'.$money.',
					"content"=>"充值'.$money.'元，"
				));					
			',
			"url"=>$backurl
		);
		$orderdata=base64_encode(json_encode($orderdata));
		$order_price=$money;
		/*****插入充值表******/
		M("recharge")->insert(array(
		 
			"money"=>$order_price,
			"pay_type"=>$pay_type,
			"orderno"=>$orderno,
			"orderinfo"=>$order_product, 
			"type_id"=>1,
			"tablename"=>"wmo2o_shop_money",
			"objectid"=>SHOPID,
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
	}
	
	
	
}