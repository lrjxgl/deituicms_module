<?php
class wmo2o_offpayControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$shopid=get("shopid","i");
		$shop=MM("wmo2o","wmo2o_shop")->get($shopid,"shopid,shopname,imgurl");
		$this->smarty->goAssign(array(
			"shop"=>$shop
		));
		$this->smarty->display("wmo2o_offpay/index.html");
	}
	
	public function onOrder(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$shopid=post("shopid","i");
		$money=post("money","f");
		if($money<=0){
			$this->goAll("金额不能小于0",1);
		}
		$content=post("content","h");
		$orderid=M("mod_wmo2o_offpay")->insert(array(
			"userid"=>$userid,
			"shopid"=>$shopid,
			"money"=>$money,
			"content"=>$content,
			"createtime"=>date("Y-m-d H:i:s")
		));
		$_GET["orderid"]=$orderid;
		$this->onPay();
	}
	/***生成支付*****/
	public function onPay($return=0){
		$userid=M("login")->userid;
		$orderno="Re".M("maxid")->get();
		$orderid=get("orderid","i");
		$order=MM("wmo2o","wmo2o_offpay")->selectRow("orderid=".$orderid);
		//生成支付
		
		$backurl=get_post("backurl","x");
		if($backurl==""){
			$backurl="/module.php?m=wmo2o_order&a=success";
		}
		$pay_type=INWEIXIN?"wxpay":"alipay";
		$orderdata=array(
			"table"=>"plugin",
			"callback"=>'
				MM("wmo2o","wmo2o_offpay")->pay('.$orderid.',array(
					 
					"recharge_id"=>"$recharge_id",
					"paytype"=>"'.$paytype.'",
				));
				 
			',
			"url"=>$backurl
		);
		$orderdata=base64_encode(json_encode($orderdata)); 
		$orderinfo=date("Y-m-d H:i:s")."在线优惠买单";
		$order_product=date("Y-m-d H:i:s")."在线优惠买单";
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
	
	public function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$start=get("per_page","i");
		$limit=24;
		$where=" userid=".$userid." AND status in(0,1,2) ";
		$url="/module.php?m=wmo2o_offpay&a=my";
		$ops=array(
			"where"=>$where,
			"start"=>$start,
			"limit"=>$limit,
			"order"=>"orderid DESC"
		);
		$rscount=true;
		$list=MM("wmo2o","wmo2o_offpay")->select($ops,$rscount);
		$shopids=[];
		if(!empty($list)){
			foreach($list as $v){
				$shopids[]=$v["shopid"];
			}
			$sps=MM("wmo2o","wmo2o_shop")->getListByIds($shopids,"shopid,shopname,imgurl");
			foreach($list as $k=>$v){
				$v["shop"]=$sps[$v["shopid"]];
				$list[$k]=$v;
			}
		}
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,
			"rscount"=>$rscount,
			"pagelist"=>$pagelist
		));
		$this->smarty->display("wmo2o_offpay/my.html");
	}
	
}