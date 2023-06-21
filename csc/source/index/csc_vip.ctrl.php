<?php
	class csc_vipControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		
		public function onDefault(){
			$userid=M("login")->userid;
			$list=M("mod_csc_vip")->select(array(
				"where"=>" status=1 "
			));
			if($list){
				foreach($list as $k=>$v){
					$v["description"]=nl2br($v["description"]);
					$list[$k]=$v;
				}
			}
			$myvip=MM("csc","csc_vip_user")->get($userid,SHOPID);
			
			$this->smarty->goAssign(array(
				"list"=>$list,
				"myvip"=>$myvip
			));
			$this->smarty->display("csc_vip/index.html");
		}
		 
		public function onSuccess(){
			
			$this->smarty->display("csc_vip/success.html");
		}
		
		public function onBuy(){
			$vipid=post("vipid","i");
			$vip_day=post("vip_day","h");
			$money=post("money","h");
			$userid=M("login")->userid;
			$myVip=MM("csc","csc_vip_user")->get($userid);
			if($myVip && $myVip["vipid"]>$vipid){
				$this->goAll("vip无法降级，等待当前vip结束再续期");
			}
			$vip=M("mod_csc_vip")->selectRow("vipid=".$vipid." AND status=1 ");
			if(!$vip){
				$this->goAll("该vip已经下架咯",1);
			}
			if($vip_day=='month'){
				$money=$vip["month_money"];
			}else{
				$money=$vip["year_money"];
			}
			//生成支付
			$orderno="Re".M("maxid")->get();
			$backurl=get_post("backurl","x");
			if($backurl==""){
				$backurl="/module.php?m=csc_vip&a=success"; 
			}
			$pay_type=INWEIXIN?"wxpay":"alipay";
			$orderdata=array(
				"table"=>"plugin",
				"callback"=>'
					MM("csc","csc_vip_user")->add(array(
						"userid"=>'.$userid.',
						"vipid"=>'.$vipid.',
						"vip_day"=>"'.$vip_day.'",
						"shopid"=>"'.SHOPID.'"
					));
				',
				"url"=>$backurl
			);
			$orderdata=base64_encode(json_encode($orderdata)); 
			$orderinfo=date("Y-m-d H:i:s")."充值".$vip["title"]."会员卡";
			$order_product=date("Y-m-d H:i:s")."充值".$vip["title"]."会员卡";
			$fromapp=get("fromapp");		 
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