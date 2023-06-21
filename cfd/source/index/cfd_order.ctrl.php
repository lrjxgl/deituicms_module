<?php
class cfd_orderControl extends skymvc{
	
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
		$cfdid=get_post('cfdid','i');
		$cfd=M("mod_cfd")->selectRow("cfdid=".$cfdid);
		 
		if(!$cfd || $cfd["status"]!=1){
			$this->goAll("数据出错",1);
		}
		$userid=M("login")->userid;
		$rewardid=get_post('rewardid','i');
		
		$reward=M("mod_cfd_reward")->selectRow("id=".$rewardid);
		if(empty($reward) || $reward["status"]!=1 ){
			$this->goAll("出错了",1);
		}
		$money=$reward['money'];
		$orderid=M("mod_cfd_order")->insert(array(
			"userid"=>$userid,
			"cfdid"=>$cfdid,
			"money"=>$money,
			"rewardid"=>$rewardid,
			"typeid"=>$reward['typeid'],
			"createtime"=>date("Y-m-d H:i:s"),
		));
		$pdata=array(
			"orderid"=>$orderid,
			"gopay"=>1,
			"url"=>"/module.php?m=cfd_order&a=pay&orderid=".$orderid
		);
		$this->goAll("支持成功",0,$pdata);
	}
	
	
	
	public function onPay($orderid=0){
		$orderid=$orderid?$orderid:get('orderid','i');
		$userid=M("login")->userid;
		$order=M("mod_cfd_order")->selectRow("orderid=".$orderid);
		$cfdid=$order['cfdid'];
		if($order['ispay']){
			$this->goAll("已经支付过了",1);
		}
		$cfd=M("mod_cfd")->selectRow("cfdid=".$cfdid);
		if($cfd['status']!=1){
			$this->goAll("该众筹已下线",1);
		}

		if(ALIPAY!=1 && WXPAY!=1){
			$this->goAll("支付未配置无法进行支付操作",1);
		}
		if(INWEIXIN==true){
			$pay_type="wxpay";
		}else{
			$pay_type="alipay";
		}
		 
		$order_product=$cfd["title"];
		$orderno="re".date("YmdHis").M("login")->userid;
		$backurl="/module.php?m=cfd&a=show&cfdid=".$order['cfdid'];
		$orderdata=array(
			"table"=>"plugin",
			"callback"=>'
				M("mod_cfd_order")->update(array("status"=>2,"ispay"=>1),"orderid='.$orderid.'");
				M("mod_cfd")->changenum("joinnum",1,"cfdid='.$cfdid.'");
				M("mod_cfd")->changenum("joinmoney",'.$order['money'].',"cfdid='.$cfdid.'");
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
			"orderinfo"=>"创业众筹", 
			"type_id"=>1,
			"tablename"=>"",
			"dateline"=>time(),
			"status"=>2,
	
			"orderdata"=>$orderdata,
		));
		
		/*插入充值表结束*/
		
		$url=HTTP_HOST."/index.php?m=recharge_".$pay_type."&a=go";
		$url.="&orderno=".$orderno;
		$url.="&bank_type=".$bank_type;
		$url.="&order_product=".urlencode($order_product);
		$url.="&order_price=".$order_price;
		$url.="&order_info=".urlencode($order_info);
		$url.="&backurl=".base64_encode($backurl);
		$redata=array(
			"payurl"=>$url,
			"action"=>"pay",
			"orderno"=>$orderno
		);
		if($return){
			return $redata;
		}
		//end 固定支付
		if(!$_GET["ajax"]){
			header("Location: ".$url);
			exit;
		}else{
			$this->goALl("正在前往支付",0,$redata,$url);
		}
		
	}
	
	public function onMy(){
		$userid=M("login")->userid;
		$where=" userid=".$userid;
		$type=get("type","h");
		switch($type){
			case "unpay":
				$where.=" AND ispay=0 AND status=0 ";
				break;
			case "unfinish":
				$where.=" AND ispay=1 AND isreward=0 ";
				break;
			case "finish":
				$where.=" AND ispay=1 AND isreward=1 ";
				break;
			default:
				$where.=" AND status in(0,1,2,3,4) ";
				break;
				
		}
		$option=array(
			"where"=>$where,
			"order"=>"orderid DESC"
		);
		$data=M("mod_cfd_order")->select($option);
		if($data){
			foreach($data as $v){
				$ids[]=$v['cfdid'];
				$rewardids[]=$v['rewardid'];
			}
			$cfds=MM("cfd","cfd")->getListByIds($ids,"cfdid,title");
			$rewards=MM("cfd","cfd_reward")->getListByIds($rewardids,"id,title");
			 
			foreach($data as $k=>$v){
				$v['cfd']=$cfds[$v['cfdid']];
				$v['reward']=$rewards[$v['rewardid']];
				if($v["ispay"]==0){
					$v["status_name"]="待支付";
				}elseif($v["isreward"]==1){
					$v["status_name"]="已回报";
				}else{
					$v["status_name"]="待回报";
				}
				
				$data[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"data"=>$data
		));
		
		$this->smarty->display("cfd_order/my.html");
	}
	
	public function onShow(){
		$orderid=get("orderid","i");
		$order=M("mod_cfd_order")->selectRow("orderid=".$orderid);
		$cfdid=$order["cfdid"];
		$cfd=M("mod_cfd")->selectRow(array(
			"where"=>" cfdid=".$cfdid 
		));
		$userid=M("login")->userid;
		if($order["userid"]!=$userid){
			$this->goAll("无权管理",1);
		}
		$reward=M("mod_cfd_reward")->selectRow("id=".$order["rewardid"]);
		if($order["ispay"]==0){
			$order["status_name"]="待支付";
		}elseif($order["isreward"]==1){
			$order["status_name"]="已回报";
		}else{
			$order["status_name"]="待回报";
		}
		$this->smarty->goAssign(array(
			"order"=>$order,
			"cfd"=>$cfd,
			"reward"=>$reward
		));
		$this->smarty->display("cfd_order/show.html");
	}
}
?>