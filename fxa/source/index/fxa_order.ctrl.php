<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fxa_orderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			MM("fxa","fxa_order")->paySuccess(7);
		}
		public function onConfirm(){
			$productid=get("productid","i");
			$product=M("mod_fxa_product")->selectRow("id=".$productid);
			//检测是否可购买
			if(empty($product) || $product["status"]!=1 || $product["etime"]<time()){
				$this->goAll("该活动已暂停了",1);
			}
			$this->smarty->assign(array(
				"product"=>$product
			));
			$this->smarty->display("fxa_order/confirm.html");
		}
		public function onOrder(){
			$productid=get_post("productid","i");
			$product=M("mod_fxa_product")->selectRow("id=".$productid);
			//检测是否可购买
			if(empty($product) || $product["status"]!=1 || $product["etime"]<time()){
				$this->goAll("该活动已暂停了",1);
			}
			$userid=M("login")->userid;
			$invite_userid=0;
			if(isset($_SESSION["ssIcode"])){
				$invite_userid=$_SESSION["ssIcode"];
			}
			$nickname=post("nickname","h");
			$telephone=post("telephone","h");
			$address=post("address","h");
			$orderid=M("mod_fxa_order")->insert(array(
				"userid"=>$userid,
				"createtime"=>date("Y-m-d H:i:s"),
				"productid"=>$product["id"],
				"money"=>$product["price"],
				"fx_money"=>$product["fx_money"],
				"shop_money"=>$product["shop_money"],
				"shopid"=>$product["shopid"],
				"invite_userid"=>$invite_userid,
				"nickname"=>$nickname,
				"telephone"=>$telephone,
				"address"=>$address,
				"send_type"=>$product["send_type"]
			));
			/*
			$order=M("mod_fxa_order")->selectRow(array(
				"where"=>"productid=".$productid." AND ispay=0 AND userid=".$userid,
				"order"=>"orderid DESC"
			));
			if($order){
				$orderid=$order["orderid"];
				if($order["ispay"]==1){
					$this->goAll("你已经购买过了",1);
				}
				
			}else{
				$invite_userid=0;
				if(isset($_SESSION["ssIcode"])){
					$invite_userid=$_SESSION["ssIcode"];
				}
				$nickname=post("nickname","h");
				$telephone=post("telephone","h");
				$address=post("address","h");
				$orderid=M("mod_fxa_order")->insert(array(
					"userid"=>$userid,
					"createtime"=>date("Y-m-d H:i:s"),
					"productid"=>$product["id"],
					"money"=>$product["price"],
					"fx_money"=>$product["fx_money"],
					"shop_money"=>$product["shop_money"],
					"shopid"=>$product["shopid"],
					"invite_userid"=>$invite_userid,
					"nickname"=>$nickname,
					"telephone"=>$telephone,
					"address"=>$address
				));
			}
			*/
			 
			$_GET["orderid"]=$orderid;
			$payData=$this->onPay(true);
			$pdata=array(
				"orderid"=>$orderid,
				"gopay"=>$gopay,
				"payurl"=>$payData["payurl"],
				"action"=>"pay",
				"orderno"=>$payData["orderno"],
				"url"=>"/module.php?m=fxa_order&a=pay&orderid=".$orderid
			);
			$this->goAll("感谢您的支持，请继续支付订单",0,$pdata);
		}
		public function onPay($return=false){
			$orderid=get('orderid','i');
			$userid=M("login")->userid;
			$order=M("mod_fxa_order")->selectRow("orderid=".$orderid);
			$productid=$order['productid'];
			if($order['ispay']){
				$this->goAll("已经支付过了",1);
			}
			$product=M("mod_fxa_product")->selectRow("id=".$productid);
			if($product['status']!=1){
				$this->goAll("该活动已下线",1);
			}
			 
			if(ALIPAY!=1 && WXPAY!=1){
				$this->goAll("支付未配置无法进行支付操作",1);
			}
			if(INWEIXIN==true && WXPAY==1){
				$pay_type="wxpay";
			}else{
				$pay_type="alipay";
			}
			 
			$order_product=$product["title"];
			$orderno="re".M("maxid")->get();
			$backurl="/module.php?m=fxa_product&a=show&id=".$order['productid'];
			$orderdata=array(
				"table"=>"plugin",
				"callback"=>'
					MM("fxa","fxa_order")->paySuccess('.$orderid.');			
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
				"orderinfo"=>$order_product, 
				"type_id"=>1,
				"tablename"=>"",
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
			exit;
		}
		public function onMy(){
			$userid=M("login")->userid;
			$where=" ispay=1 AND userid=".$userid;
			$url="/module.php?m=fxa_order&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("fxa","fxa_order")->Dselect($option,$rscount);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("fxa_order/my.html");
		}
		
		public function onShow(){
			$orderid=get_post("orderid","i");
			$data=M("mod_fxa_order")->selectRow(array("where"=>"orderid=".$orderid));
			$product=M("mod_fxa_product")->selectRow("id=".$data["productid"]);
			$product["imgurl"]=images_site($product["imgurl"]);
			//生成二维码
			require "extends/hashids/Hashids.php";
			$hashids = new Hashids\Hashids(date("Y-m-d H"),8);
			$yzm=$hashids->encode($orderid);
			$qrcode=HTTP_HOST."/module.php?m=fxa_shop&a=checkorder&yzm=".$yzm;
			$ewm="/index.php?m=qrcode&content=".urlencode($qrcode);
			$statusList=MM("fxa","fxa_order")->statusList();
			$data["status_name"]=$statusList[$data["status"]];
			$this->smarty->goassign(array(
				"order"=>$data,
				"product"=>$product,
				"ewm"=>$ewm
			));
			$this->smarty->display("fxa_order/show.html");
		}
		
		 
	}

?>