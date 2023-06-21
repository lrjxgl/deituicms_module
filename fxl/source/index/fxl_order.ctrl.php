<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fxl_orderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			 
		}
		public function onOrder(){
			M("login")->checkLogin();
			$fxlid=get_post('fxlid','i');
			$fxl=M("mod_fxl")->selectRow("fxlid=".$fxlid);
			if(!$fxl || $fxl["status"]!=1){
				$this->goAll("数据出错",1);
			}
			$userid=M("login")->userid;
			$nickname=post("nickname","h");
			if(empty($nickname)){
				$this->goAll("捐赠人不能为空",1);
			}
			$money=post("money","i");
			if(empty($money)){
				$this->goAll("捐赠金额不能为空",1);
			}
			$orderid=M("mod_fxl_order")->insert(array(
				"userid"=>$userid,
				"fxlid"=>$fxlid,
				"money"=>$money,
				"nickname"=>$nickname,
				"createtime"=>date("Y-m-d H:i:s"),
			));
			$pdata=array(
				"orderid"=>$orderid,
				"gopay"=>1,
				"url"=>"/module.php?m=fxl_order&a=pay&orderid=".$orderid
			);
			$this->goAll("支持成功",0,$pdata);
		}
		
		
		
		public function onPay($orderid=0){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$orderid=$orderid?$orderid:get('orderid','i');
			 
			$order=M("mod_fxl_order")->selectRow("orderid=".$orderid);
			$fxlid=$order['fxlid'];
			if($order['ispay']){
				$this->goAll("已经支付过了",1);
			}
			$fxl=M("mod_fxl")->selectRow("fxlid=".$fxlid);
			if($fxl['status']!=1){
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
			 
			$order_product=$fxl["title"];
			$orderno="re".date("YmdHis").M("login")->userid;
			$backurl="/module.php?m=fxl&a=show&fxlid=".$order['fxlid'];
			$orderdata=array(
				"table"=>"plugin",
				"callback"=>'
					M("mod_fxl_order")->update(array("status"=>2,"ispay"=>1),"orderid='.$orderid.'");
					M("mod_fxl")->changenum("joinnum",1,"fxlid='.$fxlid.'");
					M("mod_fxl")->changenum("joinmoney",'.$order['money'].',"fxlid='.$fxlid.'");
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
				"orderinfo"=>"慈善众筹".$fxl["title"], 
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
		
		public function onList(){
			$fxlid=get("fxlid","i");
			$where=" fxlid=".$fxlid." AND ispay=1 AND status in(0,1,2)";
			$url="/module.php?m=fxl_order&a=list&fxlid=".$fxlid;
			$limit=2000;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderid DESC",
				"where"=>$where,
				"fields"=>"userid,nickname,orderid,money,createtime"
			);
			$rscount=true;
			$data=M("mod_fxl_order")->select($option,$rscount);
			 
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
			$this->smarty->display("fxl_order/index.html");
		}
		
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" status<4 AND userid=".$userid;
			$url="/module.php?m=fxl_order&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fxl_order")->select($option,$rscount);
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
			$this->smarty->display("fxl_order/index.html");
		}
		
		public function onShow(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$orderid=get_post("orderid","i");
			$data=M("mod_fxl_order")->selectRow(array("where"=>"orderid=".$orderid));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("fxl_order/show.html");
		}
		 
		
		
	}

?>