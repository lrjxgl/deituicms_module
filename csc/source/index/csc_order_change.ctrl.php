<?php
class csc_order_changeControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$type=get("type","h");
		if($type==''){
			$type='all';
		}
		$where=" shopid=".SHOPID;
		$url="/moduleshop.php?m=csc_order_change";
		$limit=24;
		$start=get("per_page","i");
		switch($type){
			case "unpay":
				$where.=" AND ispay=0 ";
				break;
			case "ispay":
				$where.=" AND ispay=1 ";
				break;
		} 
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" orderid DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=M("mod_csc_order_change")->select($option,$rscount);
		if($data){
			foreach($data as $v){
				$oids[]=$v["orderid"];
			}
			$ods=MM("csc","csc_order")->getListByIds($oids);
			foreach($data as $k=>$v){
				$p=$ods[$v['orderid']];
				$p["nmoney"]=$v["money"];
				$p["ncontent"]=$v["content"];
				$p["ispay"]=$v["ispay"];
				$data[$k]=$p;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$data,
			"type"=>$type
		));
		$this->smarty->display("csc_order_change/index.html");
	}
	public function onSuccess(){
		$this->smarty->display("csc_order_change/success.html");
	}
	public function onPay($return=0){
		$userid=M("login")->userid;
		$orderno="Re".M("maxid")->get();
		$orderid=get("orderid","i");
		$change=M("mod_csc_order_change")->selectRow("orderid=".$orderid);
		if($change["status"]>2){
			$this->goAll("该单已处理，无法支付",1);
		}
		if($change["typeid"]!=2 || $change["money"]<=0){
			$this->goAll("无需支付",1);
		}
		if($change["ispay"]==1){
			$this->goAll("已经支付",1);
		}
		//生成支付
		
		$backurl=get_post("backurl","x");
		if($backurl==""){
			$backurl="/module.php?m=csc_order_change&a=success";
		}
		$pay_type=INWEIXIN?"wxpay":"alipay";
		$orderdata=array(
			"table"=>"plugin",
			"callback"=>'
				M("mod_csc_order_change")->update(array(
					"ispay"=>1,
					"recharge_id"=>"$recharge_id",
					"paytype"=>"'.$pay_type.'",
				),"orderid='.$orderid.'");
				
			',
			"url"=>$backurl
		);
		$orderdata=base64_encode(json_encode($orderdata)); 
		$orderinfo=date("Y-m-d H:i:s")."订单补差价";
		$order_product=date("Y-m-d H:i:s")."订单补差价";
		$fromapp=get("fromapp");
		$money= $change['money'];
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