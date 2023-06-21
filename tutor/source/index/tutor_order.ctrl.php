<?php
class tutor_orderControl extends skymvc{
	public function onInit(){
		M("login")->checkLogin();
	}
	public function onDefault(){
		
		
	}
	
	public function onSave(){
		$lessonid=post("lessonid","i");
		$lesson=M("mod_tutor_lesson")->selectRow("lessonid=".$lessonid);
		if($lesson["status"]!=1){
			$this->goAll("课程已下架",1);
		}
		if($lesson["buy_num"]>=$lesson["total_num"]){
			$this->goAll("课程已售完",1);
		}
		$userid=M("login")->userid;
		$nickname=post("nickname","h");
		$address=post("address","h");
		$telephone=post("telephone","h");
		M("user_lastaddr")->add(array(
			"nickname"=>$nickname,
			"address"=>$address,
			"telephone"=>$telephone
		),$userid);
		$orderid=M("mod_tutor_order")->insert(array(
			"userid"=>$userid,
			"lessonid"=>$lessonid,
			"shopid"=>$lesson["shopid"],
			"nickname"=>$nickname,
			"address"=>$address,
			"telephone"=>$telephone,
			"money"=>$lesson["money"],
			"dateline"=>time(),
			"ispay"=>0
		));
		 
		$_GET["orderid"]=$orderid;
		$payData=$this->onPay(true);
		$redata=array(
			"payurl"=>$payData["payurl"],
			"action"=>"pay",
		);
		$this->goAll("下单成功",0,$redata);
	}
	
	public function onPay($return=false){
		$orderid=get('orderid','i');
		$userid=M("login")->userid;
		$order=M("mod_tutor_order")->selectRow("orderid=".$orderid);
		 
		if($order['ispay']){
			$this->goAll("已经支付过了",1);
		}
		$lesson=M("mod_tutor_lesson")->selectRow("lessonid=".$order["lessonid"]);
		if($lesson['status']!=1){
			$this->goAll("该商品已下线",1);
		}
		if($lesson["sold_num"]>=$lesson["total_num"]){
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
		 
		$order_product=$lesson["title"];
		$orderno="re".M("maxid")->get();
		$backurl="/module.php?m=tutor_order&a=success&lessonid=".$order['lessonid'];
		$orderdata=array(
			"table"=>"plugin",
			"callback"=>'
				M("mod_tutor_order")->update(array(
					"ispay"=>1,
					"recharge_id"=>"$recharge_id",
					"paytype"=>"'.$pay_type.'",
				),"orderid='.$orderid.'");
						
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
			"orderinfo"=>$lesson['title'], 
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
	
	public function onShow(){
		$orderid=get("orderid","i");
		$userid=M("login")->userid;
		$row=MM("tutor","tutor_order")->selectRow("orderid=".$orderid);
		if($row["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		$statusList=MM("tutor","tutor_order")->statusList();
		$row["status_name"]=$statusList[$row["status"]];
		$logList=M("mod_tutor_order_log")->select(array(
			"where"=>"orderid=".$orderid,
			"order"=>"id ASC"
		));
		$raty=[];
		if($row["israty"]){
			$raty=M("mod_tutor_order_raty")->selectRow("orderid=".$orderid);
		}
		$shop=MM("tutor","tutor_shop")->get($row["shopid"]);
		$lesson=MM("tutor","tutor_lesson")->get($row["lessonid"]);
		$this->smarty->goAssign(array(
			"data"=>$row,
			"logList"=>$logList,
			"raty"=>$raty,
			"shop"=>$shop,
			"lesson"=>$lesson
		));
		$this->smarty->display("tutor_order/show.html");
	}
	
	public function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$where=" userid=".$userid." ";
		$type=get("type","h");
		switch($type){
			case "new":
				$where.=" AND status=0 ";
				break;
			case "confirm":
				$where.=" AND status=1 ";
				break;
			case "send":
				$where.=" AND status=2 ";
				break;
			case "finish":
				$where.=" AND status=3 ";
				break;
			case "cancel":
				$where.=" AND status=4 ";
				break;
			case "unraty":
				$where.=" AND status=3 AND israty=0 ";
				break;	
			default:
				$where.=" AND status in(0,1,2,3,4) ";
				break;
		}
		$url="/moduleadmin.php?m=tutor_order&a=my";
		$limit=20;
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" orderid DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=M("mod_tutor_order")->select($option,$rscount);
		$statusList=array(
			0=>"未接单",
			1=>"处理中",
			3=>"已完成",
			4=>"已取消"
		);
		if($data){
			$shopids=[];
			$lsids=[];
			foreach($data as $v){
				$shopids[]=$v["shopid"];
				$lsids[]=$v["lessonid"];
			}
			$sps=MM("tutor","tutor_shop")->getListByIds($shopids,"shopid,title,imgurl,description");
			$lss=MM("tutor","tutor_lesson")->getListByIds($lsids,"lessonid,title,imgurl,description,lesson_num");
			foreach($data as $k=>$v){
				$v["shop"]=$sps[$v["shopid"]];
				$v["lesson"]=$lss[$v["lessonid"]];
				
				$v["status_name"]=$statusList[$v["status"]];
				if($v["ispay"]==0 && $v["status"]==0){
					$v["status_name"]="未支付";
				}
				$data[$k]=$v;
			}
		}
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
		$this->smarty->display("tutor_order/my.html");
	}
	
	
}