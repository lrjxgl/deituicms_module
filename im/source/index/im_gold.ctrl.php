<?php
class im_goldControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$list=M("mod_im_gold")->select(array(
			"where"=>" status=1 ",
			"order"=>" gold ASC"
		));
		$redata=array(
			"list"=>$list
		);
		$this->goAll("success",0,$redata);
	}
	
	public function onBuy(){
		$userid=M("login")->userid;
		$id=get("id","i");
		$row=M("mod_im_gold")->selectRow("id=".$id);
		if(!$row || $row["status"]!=1){
			$this->goAll("数据出错",1);
		}
		$backurl=get_post("backurl","x");
		if($backurl==""){
			$backurl="/";
		}
		$orderno="Re".M("maxid")->get();
		$pay_type=INWEIXIN?"wxpay":"alipay";
		$orderdata=array(
			"table"=>"plugin",
			"callback"=>'
				M("user")->addMoney(array(
					"gold"=>'.$row["gold"].',
					"recharge_id"=>"$recharge_id",
					"paytype"=>"'.$paytype.'",
					"userid"=>'.$userid.'
				));
			',
			"url"=>$backurl
		);
		$orderdata=base64_encode(json_encode($orderdata)); 
		$orderinfo=date("Y-m-d H:i:s")."购买商品";
		$order_product=date("Y-m-d H:i:s")."购买商品";
		$fromapp=get("fromapp");
		$money= $row["price"];
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