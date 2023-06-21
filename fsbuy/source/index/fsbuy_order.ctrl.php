<?php
class fsbuy_orderControl extends skymvc{
	
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
		$fsid=get_post('fsid','i');
		$fsbuy=M("mod_fsbuy")->selectRow("fsid=".$fsid);
		if(!$fsbuy){
			$this->goAll("数据出错",1);
		}
		if($fsbuy['status']!=2){
			$this->goAll("该团购已下线",1);
		}
		if($fsbuy["maxnum"]==$fsbuy["buynum"]){
			$this->goAll("该商品已经被抢光啦",1);
		}
		$userid=M("login")->userid;
		$row=M("mod_fsbuy_order")->selectRow("userid=".$userid." AND fsid=".$fsid);
		if($row){
			$this->goAll("你已经参与过了",1);
		}
		
		$address=post('address','h');
		$nickname=post('nickname','h');
		$telephone=post('telephone','h');
		if(empty($address) || empty($nickname) || empty($telephone)){
			$this->goAll("请确认联系方式",1);
		}
		M("user_lastaddr")->add(array(
			"address"=>$address,
			"nickname"=>$nickname,
			"telephone"=>$telephone
			
		),$userid);
		//款式处理
		if($fsbuy["isksid"]){
			$ksids=post("ksids");
			if(empty($ksids)){
				$this->goAll("请选择款式",1);
			}
			$kss=explode(",",$ksids);
			$kids=array();
			$ksjson="";
			foreach($kss as $ksid){
				if($ksid>0){
					$kids[]=intval($ksid);
				}
				
			}
			$ksList=M("mod_fsbuy_ks")->select(array(
				"where"=>"fsid=".$fsid." AND ksid in("._implode($kids).")  ",
				"fields"=>"fsid,ksid,title,price"
			));
			if(empty($ksList)){
				$this->goAll("请选择款式");
			}
			$money=0;
			$ksarr=array();
			$kjarr=array();
			$products=$fsbuy["title"];
			$kstitle="";
			foreach($ksList as $ks){
				$money+=$ks["price"];
				$ksarr[]=$ks["ksid"];
				$kjarr[]=$ks;
				$products.="，".$ks["title"];
				$kstitle.=$ks["title"] ." ";
			}
			$ksids=implode(",",$ksarr);
			$ksjson=arr2str($kjarr);
		}else{
			$ksids="";
			$ksjson="";
			$kstitle="";
			$money=$fsbuy['price'];
			$products=$fsbuy["title"];
		}

		$user=M("user")->selectRow(array(
			"where"=>" userid=".$userid,
			"fields"=>" userid,nickname,money"
		));
		$ispay=0;
		$gopay=1;
		M("user")->begin();
		if($user["money"]>=$money ){
			$ispay=1;
			$gopay=0;
			M("user")->addMoney(array(
				"userid"=>$userid,
				"money"=>-$money,
				"content"=>"您购买了{$products},花了{$money}元"
			));	
				
		}
		$invite_fsuserid=get("invite_fsuserid","i");
		if(!$invite_fsuserid && isset($_SESSION["ss_invite_fsuserid"])){
			$invite_fsuserid=intval($_SESSION["ss_invite_fsuserid"]);
		}elseif(isset($_COOKIE["ck_invite_fsuserid"])){
			$invite_fsuserid=intval($_COOKIE["ck_invite_fsuserid"]);
		}
		$orderid=M("mod_fsbuy_order")->insert(array(
			"userid"=>$userid,
			"fstype"=>$data["fstype"],
			"fsid"=>$fsid,
			"money"=>$money,
			"nickname"=>$nickname,
			"address"=>$address,
			"telephone"=>$telephone, 
			"createtime"=>date("Y-m-d H:i:s"),
			"ispay"=>$ispay,
			"ksids"=>$ksids,
			"ksjson"=>$ksjson,
			"kstitle"=>$kstitle,
			"invite_fsuserid"=>$invite_fsuserid
		));
		//增加销量
		M("mod_fsbuy")->changenum("buynum",1,"fsid=".$fsid);
		M("user")->commit();
		if($gopay==1){
			$_GET["orderid"]=$orderid;
			$payData=$this->onPay(true);
			$pdata=array(
				"orderid"=>$orderid,
				"gopay"=>$gopay,
				"payurl"=>$payData["payurl"],
				"action"=>"pay",
				"orderno"=>$payData["orderno"],
				"url"=>"/module.php?m=fsbuy_order&a=pay&orderid=".$orderid
			);
		}else{
			$pdata=array(
				"orderid"=>$orderid,
				"gopay"=>$gopay,
				"url"=>"/module.php?m=fsbuy_order&a=pay&orderid=".$orderid
			);
		}
		
		$this->goAll("感谢您的支持，请继续支付订单",0,$pdata);
	}
	
	
	
	public function onPay($return=false){
		$orderid=get('orderid','i');
		$userid=M("login")->userid;
		$order=M("mod_fsbuy_order")->selectRow("orderid=".$orderid);
		$fsid=$order['fsid'];
		if($order['ispay']){
			$this->goAll("已经支付过了",1);
		}
		$fsbuy=M("mod_fsbuy")->selectRow("fsid=".$fsid);
		if($fsbuy['status']!=2){
			$this->goAll("该团购已下线",1);
		}
		if($fsbuy["maxnum"]==$fsbuy["buynum"]-1){
			$this->goAll("该商品已经被抢光啦",1);
		}
		if(ALIPAY!=1 && WXPAY!=1){
			$this->goAll("支付未配置无法进行支付操作",1);
		}
		if(INWEIXIN==true && WXPAY==1){
			$pay_type="wxpay";
		}else{
			$pay_type="alipay";
		}
		 
		$order_product=$fsbuy["title"];
		$orderno="re".M("maxid")->get();
		$backurl="/module.php?m=fsbuy&a=success&fsid=".$order['fsid'];
		$orderdata=array(
			"table"=>"plugin",
			"callback"=>'
				MM("fsbuy","fsbuy_order")->paySuccess(array(
					"ispay"=>1,
					"recharge_id"=>"$recharge_id",
					"paytype"=>"'.$pay_type.'",
				),'.$orderid.');
						
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
			"orderinfo"=>$fsbuy['title'], 
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
	public function onSuccess(){
		
		$this->smarty->display("fsbuy/success.html");
	}
	public function onMy(){
		$userid=M("login")->userid;
		$where=" userid=".$userid;
		 
		$type=get("type","h");
		switch($type){
			case "unpay":
				$where.=" AND status=0 AND ispay=0 ";
				break;
			case "unraty":
				$where.=" AND status=3 AND israty=0";
				break;
			case "unpin":
				$where.=" AND pin_success=0 AND status=0 AND ispay=1 ";
				break;
			case "unreceive":
				$where.=" AND pin_success=1 AND status in(0,1,2) AND ispay=1 ";
				break;
			default:
				$where.=" AND status in(0,1,2,3,4) ";
				break;
		}
		$option=array(
			"where"=>$where,
			"order"=>"orderid DESC"
		);
		$data=MM("fsbuy","fsbuy_order")->select($option);
		$statuslist=MM("fsbuy","fsbuy_order")->statuslist();
		if($data){
			foreach($data as $v){
				$ids[]=$v['fsid'];
				 
			}
			$fsbuys=MM("fsbuy","fsbuy")->getListByIds($ids);
			 
			foreach($data as $k=>$v){
				 
				$v['fsbuy']=$fsbuys[$v['fsid']];
			 	$v['timeago']=timeago(strtotime($v['createtime']));
				$v['status_name']=MM("fsbuy","fsbuy_order")->getStatus($v);
				$data[$k]=$v;
			}
		}
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$this->smarty->goAssign(array(
			"list"=>$data,
			"per_page"=>$per_page,
			"statuslist"=>$statuslist
			
		));
		
		$this->smarty->display("fsbuy_order/my.html");
	}
	public function onShow(){
		$orderid=get_post("orderid","i");
		$order=MM("fsbuy","fsbuy_order")->selectRow("orderid=".$orderid);
		$discount_money=0;
		$userid=M("login")->userid;
		if($order["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		$fsbuy=M("mod_fsbuy")->selectRow(array(
			"where"=>" fsid=".$order["fsid"],
			"fields"=>"fsid,imgurl,title,price,step_config,buynum"
		));
		if($order["fstype"]==2){
			$step_config=MM("fsbuy","fsbuy")->parseStepConfig($fsbuy["step_config"],$fsbuy["buynum"]);
			$discount=MM("fsbuy","fsbuy")->getStepConfigDiscount($step_config);
			 
			$discount_money=$order["money"]*(100-$discount)/100;
		} 
		$fsbuy["imgurl"]=images_site($fsbuy["imgurl"]);
		$ordercode="";
		$orderCodeEwm="";
		if($order["ispay"] && $order["pin_success"] && $order["status"]<3){
			$ordercode=MM("fsbuy","fsbuy_order_code")->get($orderid,$order["shopid"]);
			$c=json_encode(array(
				"action"=>"url",
				"url"=>"../../pagefsbuy/fsbuy_order_code/index?ordercode=".$ordercode
			));
			$orderCodeEwm=HTTP_HOST."/index.php?m=qrcode&content=".urlencode($c);
		}
		$order["status_name"]=MM("fsbuy","fsbuy_order")->getStatus($order);
		$this->smarty->goAssign(array(
			"order"=>$order,
			"fsbuy"=>$fsbuy,
			"ordercode"=>$ordercode,
			"orderCodeEwm"=>$orderCodeEwm,
			"discount_money"=>$discount_money
		));
		$this->smarty->display("fsbuy_order/show.html");
	}
	public function onReceive(){
		$userid=M("login")->userid;
		$orderid=get_post("orderid","i");
		$order=MM("fsbuy","fsbuy_order")->selectRow("orderid=".$orderid);
		if($order["ispay"]==0){
			$this->goAll("该订单还未支付",1);
		}
		if($order["status"]>2){
			$this->goAll("该订单已处理",1);
		}
		if($order["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		MM("fsbuy","fsbuy_order")->update(array(
			"status"=>3,
			"isreceived"=>1
		),"orderid=".$orderid);
		//处理邀请用户赏金
		MM("fsbuy","fsbuy_order")->doInvite($order);
	
		$this->goAll("订单收货完成，请评价一下吧");
	}
	public function onRatySave(){
		$userid=M("login")->userid;
		$orderid=get_post("orderid","i");
		$order=MM("fsbuy","fsbuy_order")->selectRow("orderid=".$orderid);
		if($order["ispay"]==0){
			$this->goAll("该订单还未支付",1);
		}
		if($order["status"]!=3){
			$this->goAll("该订单暂时无法评价",1);
		}
		if($order["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		if(!$order["israty"]){
			MM("fsbuy","fsbuy_order")->update(array(
				"israty"=>1
			),"orderid=".$orderid);
			$ratyData=M("mod_fsbuy_order_raty")->postData();
			$ratyData["userid"]=$userid;
			$ratyData["createtime"]=date("Y-m-d H:i:s");
			$ratyData["fsid"]=$order["fsid"];
			M("mod_fsbuy_order_raty")->insert($ratyData);
			 
		}
		$this->goAll("评价成功");
	}
}
?>