<?php
class flk_shop_payControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		M("login")->checkLogin();
		$shopid=get("shopid","i");
		$shop=MM("flk","flk_shop")->get($shopid,"shopid,shopname,imgurl,flk_maxmoney,flk_discount");
		if(empty($shop)){
			$this->goAll("商家已关闭",1,"","/module.php?m=flk");	
		}	
		$daxin=false;
		if($shop["flk_new"]){
			$daxin=M("mod_flk_daxin")->selectRow(array(
				"where"=>" status=0 AND userid=".$userid,
				"order"=>"money ASC",
				"limit"=>1
			));	 
		}
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"daxin"=>$daxin,
			"flk_discount"=>FLK_DISCOUNT
		));
		$this->smarty->display("flk_shop_pay/index.html");
	}
	public function onCheckFlk(){
		$shopid=get("shopid","i");
		$shop=MM("flk","flk_shop")->get($shopid,"shopid,shopname,imgurl");
		$daxin=false;
		$goods_money=post("goods_money","i");
		if($shop["flk_new"]){
			$daxin=M("mod_flk_daxin")->selectRow(array(
				"where"=>" status=0 AND userid=".$userid." AND money>=".$goods_money,
				"order"=>"money ASC",
				"limit"=>1
			));	 
		}
		$this->smarty->goAssign(array(
			"daxin"=>$daxin,
			"flk_discount"=>FLK_DISCOUNT
		));
	}
	public function onSuccess(){
		$shopid=get("shopid","i");
		$shop=MM("flk","flk_shop")->get($shopid,"shopid,shopname,imgurl");
		$money=get("money","h");
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"money"=>$money
		));
		$this->smarty->display("flk_shop_pay/success.html");
	}
	public function onPay($return=false){
		$_GET["ajax"]=1;
		M("login")->checkLogin(); 
		$userid=M("login")->userid;
		 
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
		$order_product="店铺收款";
		$orderno="re".M("maxid")->get();
		
		$money=post("money","h");
		if($money<=0){
			$this->goAll("金额必须大于0",1);
		}
		$shopid=post("shopid","i");
		$shop=MM("flk","flk_shop")->get($shopid,"shopid,flk_discount,flk_maxmoney,flk_new,status,express_money");
		if(empty($shop)){
			$this->goAll("店铺收款功能已经关闭",1);
		}
		$backurl=get_post("backurl","x");
		if(!$backurl){
			$backurl="/module.php?m=flk_shop_pay&a=success&shopid=".$shopid."&money=".$money;
		}
		//实际支付
		$flkid=get_post("flkid","i");
		$flk_money=0;
		if($flkid){
			$flk_money=$money*0.1;
			$money+=$flk_money;
		}
		$orderdata=array(
			"table"=>"plugin",
			"callback"=>'
				MM("flk","flk_shop_money")->payMoney(array(
					"shopid"=>'.$shopid.',
					"money"=>'.$money.',
					"flk_money"=>'.$flk_money.',
					"userid"=>'.$userid.'
				));					
			',
			"url"=>$backurl
		);
		$orderdata=base64_encode(json_encode($orderdata));
		$order_price=$money;
		/*****插入充值表******/
		M("recharge")->insert(array(
			"userid"=>$userid,
			"money"=>$order_price,
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
		
		$url=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/index.php?m=recharge_{$pay_type}&a=go";
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
	
}