<?php
class olprint_orderControl extends skymvc{
	public function __construnct(){
		parent:__construnct();
	}
	public function onInit(){
		M("login")->checkLogin(true);
		if(SHOPID==0){
			if(get("ajax")){
				$this->goAll("请选择打印店",1,0,"/module.php?m=olprint_shoplist");
			}else{
				header("Location: /module.php?m=olprint_shoplist");
				exit;
			}
		}
	}
	public function onDefault(){
		
	}
	
	public function onSuccess(){
		
		$this->smarty->display("olprint_order/success.html");
	}
	public function onFail(){
		
		$this->smarty->display("olprint_order/fail.html");
	}
	
	public function onAdd(){
		 
		$ptype=1;
		$bookid=get("bookid","i"); 
		$userid=M("login")->userid; 
		$shopid=SHOPID;
		$shop=MM("olprint","olprint_shop")->get($shopid,"shopid,express_money");
		$send_money=$shop["express_money"];
		$ptypeList=MM("olprint","olprint_shop_ptype")->onlineList($shopid);
		 
		$optype=$ptypeList[1];
		$canPrint=true;
		if($bookid){
			$book=M("mod_olprint_book")->selectRow("bookid=".$bookid);
			$ptype=99;
			//判断商家是否可以打印
			$oo=(($book["page_num"]-1)*$optype["page_money"]+$optype["start_money"]);
			if($oo>$book["money"]){
				$canPrint=false;
			}
		}
		$addr=M("user_lastaddr")->get($userid);
		$this->smarty->goAssign(array(
			"ptype"=>$ptype,
			"book"=>$book,
			"bookid"=>$bookid,
			"send_money"=>$send_money,
			"ptypeList"=>$ptypeList,
			"optype"=>$optype,
			"canPrint"=>$canPrint,
			"addr"=>$addr
		));
		$this->smarty->display("olprint_order/add.html");
	}
	
	public function onOrder(){
		$userid=M("login")->userid;
		$shopid=SHOPID;
		$ptype=post("ptype","i");
		$shop=MM("olprint","olprint_shop")->get($shopid,"shopid,express_money"); 
		$send_money=$shop["express_money"];
		$page_num=post("page_num","i");
		$print_num=post("print_num","i");
		$sendtype=post("sendtype","i");
		$bookid=post("bookid","i");
		$cms=MM("olprint","olprint_shop_commission")->get($shopid);
		$ubook_money=0;
		if($bookid){
			$book=M("mod_olprint_book")->selectRow("bookid=".$bookid);
			$money=$book["money"]*$print_num;
			$page_num=$book["page_num"];
			$fileurl=$book["fileurl"];
			//判断商家是否可以打印
			$ptypeList=MM("olprint","olprint_shop_ptype")->onlineList($shopid);
			$optype=$ptypeList[1];
			$shop_money=(($book["page_num"]-1)*$optype["page_money"]+$optype["start_money"]);
			if($shop_money>$book["money"]){
				$this->goAll("当前商家不支持打印",1);
			}
			//用户分成
			
			
			$ubook_money=($book["money"]-$shop_money)*$print_num*BOOK_USER_PER;
			$shop_money=$shop_money*$print_num; 
			 
		}else{
			$fileurl=post("fileurl","h");
			 
			$ptypeList=MM("olprint","olprint_shop_ptype")->onlineList($shopid);
			  
			$optype=$ptypeList[$ptype];
			$page_money=$optype["page_money"];
			$money=(($page_num-1)*$optype["page_money"]+$optype["start_money"])*$print_num;
			$shop_money=$money; 
		}
		
		$telephone=post("telephone","h");
		$address=post("address","h");
		$nickname=post("nickname","h");
		if($sendtype==1){
			$money+=$send_money;
			$shop_money+=$send_money;
			if(empty($telephone)){
				$this->goAll("请输入电话号码",1);
			}
			if(empty($address)){
				$this->goAll("请输入收货地址",1);
			}
		}
		$shop_money=$shop_money*(100-$cms["per"])/100; 
		$content=post("content","h");
		$ispay=0;
		$action="pay";
		$user=M("user")->getUser($userid,"userid,money");
		if($user["money"]>=$money){
			$ispay=1;
			$action="finish";
			M("user")->addMoney(array(
				"userid"=>$userid,
				"money"=>$money,
				"content"=>"打印资料花了".$money."元"
			));
		}
		$imgsdata=post("imgsdata");
		$imgsdatazip="";
		if(!empty($imgsdata)){
			$arr=explode(",",$imgsdata);
			$ims=[];
			foreach($arr as $a){
				if(!empty($a)){
					$ims[]=$a;
				}
			}
			$imgsdata=implode(",",$ims);
			//生成zip
			$imgsdatazip="attach/zip/olprint".M("maxid")->get().".zip";
			$queue=new queue("zipImgList","file");
			$queue->lpush(array(
				"files"=>$ims,
				"filename"=>$imgsdatazip
			));
			//
		}
		
		$data=array(
			"shopid"=>$shopid,
			"userid"=>$userid,
			"ptype"=>$ptype,
			"page_num"=>$page_num,			
			"print_num"=>$print_num,
			"sendtype"=>$sendtype,
			"send_money"=>$send_money,
			"page_money"=>$page_money,
			"money"=>$money,
			"createtime"=>date("Y-m-d H:i:s"),
			"ispay"=>$ispay,
			"nickname"=>$nickname,
			"address"=>$address,
			"telephone"=>$telephone,
			"bookid"=>$bookid,
			"fileurl"=>$fileurl,
			"content"=>$content,
			"shop_money"=>$shop_money,
			"ubook_money"=>$ubook_money,
			"imgsdata"=>$imgsdata,
			"imgsdatazip"=>$imgsdatazip
		);
		$rdata=array(
			"action"=>$action			 
		);
		$orderid=M("mod_olprint_order")->insert($data);
		if(!$ispay){
			$_GET["orderid"]=$orderid;
			$res=$this->onPay(1);
			$rdata['payurl']=$res['payurl'];
		}
		
		$this->goAll("success",0,$rdata);
	}
	
	public function onPay($return=0){
		$orderno="Re".M("maxid")->get();
		$orderid=get("orderid",'i');
		$order=M("mod_olprint_order")->selectRow("orderid=".$orderid);
		$backurl="/module.php?m=olprint_order&a=success&orderid=".$orderid;
		$orderdata=array(
			"table"=>"plugin",
			"callback"=>'
				M("mod_olprint_order")->update(array(
					"ispay"=>1,
					"recharge_id"=>"$recharge_id",
				),"orderid='.$orderid.'");
			',
			"url"=>$backurl
		);
		$orderdata=base64_encode(json_encode($orderdata)); 
		$order_product=$orderinfo="在线打印资料";
		$pay_type=INWEIXIN?"wxpay":"alipay";
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
		$url.="&orderno=".$orderno;
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
		$userid=M("login")->userid;
		$where="  userid=".$userid;
		$url="/module.php?m=olprint_order&a=my";
		$limit=24;
		$start=get("per_page","i");
		$type=get("type","h");
		switch(get('type')){
			
			case "unraty":
				$url.="&type=unraty";
				$where.="   AND status=3 AND israty=0";
				break;
			case "unpay":
				$url.="&type=unpay";
				$where.=" AND status=0 AND ispay=0 ";
				break;
			case "unsend":
				$url.="&type=unsend";
				$where.=" AND status=1 AND ispay=1 ";
				break;
			case "finish":
				$url.="&type=unsend";
				$where.=" AND status=3 ";
				break;		
			case "unreceive":
				$url.="&type=unreceive";
				$where.=" AND status =2  ";
				break;
			case "new":
				$url.="&type=unsend";
				$where.=" AND status=0 AND ispay=1 ";
				$type="new";
				
				break;
			case "cancel":
				$url.="&type=cancel";
				$where.=" AND status=4 ";
				$type="cancel";
				break;
			default:
				$type="all";
				$where.=" AND status in(0,1,2,3,4)";
				break;
			
		}
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" orderid DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=MM("olprint","olprint_order")->Dselect($option,$rscount);
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
		$this->smarty->display("olprint_order/my.html");
		
	}
	public function onShow(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$orderid=get("orderid","i");
		$order=MM("olprint","olprint_order")->selectRow("orderid=".$orderid);
		if(empty($order)){
			$this->goAll("数据出错",1);
		}
		if($order["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		$statusList=MM("olprint","olprint_order")->statusList();
		$ptypeList=MM("olprint","olprint_ptype")->ptypeList();
		if($order["ispay"]==0 && $order["status"]==0){
			$order["status_name"]="待支付";
		}else{
			$order["status_name"]=$statusList[$order["status"]];
		}
		 
		$order["sendtype_name"]=$order["sendtype"]==0?'自取':"配送";
		 
		$order["ptype_name"]=$ptypeList[$order["ptype"]]["title"];
		$order["fileurl"]=images_site($order["fileurl"]);
		$order["filename"]=basename($fileurl);
		if($order["bookid"]){
			$book=M("mod_olprint_book")->selectRow(array(
				"where"=>"bookid=".$order["bookid"],
				"fields"=>"bookid,title"
			));
		}
		$imgList=[];
		if(!empty($order["imgsdata"])){
			$imgList=explode(",",$order["imgsdata"]);
			foreach($imgList as $k=>$v){
				$imgList[$k]=images_site($v);
			}
		}
		$this->smarty->goAssign(array(
			"order"=>$order,
			"book"=>$book,
			 "imgList"=>$imgList
		));
		$this->smarty->display("olprint_order/show.html");
	}
	
	public function onRaty(){
		
	}
	
	public function onFinish(){
		$orderid=get("orderid","i");
		$userid=M("login")->userid;
		$order=MM("olprint","olprint_order")->selectRow("orderid=".$orderid);
		if($order["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		MM("olprint","olprint_order")->finish($orderid,$order);
		$this->goAll("success");
	}
	
	public function onCancel(){
		$orderid=get("orderid","i");
		$userid=M("login")->userid;
		$order=MM("olprint","olprint_order")->selectRow("orderid=".$orderid);
		if($order["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		MM("olprint","olprint_order")->cancel($orderid,$order);
		$this->goAll("success");
	}
	
}
?>
