<?php
class tutor_orderControl extends skymvc{
	
	
	
	public function onDefault(){
	 
		$where=" shopid=".SHOPID." AND ispay=1 ";
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
		$url="/moduleshop.php?m=tutor_order&a=my";
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
				"url"=>$url,
				
			)
		);
		$this->smarty->display("tutor_order/index.html");
	}
	
	public function onShow(){
		$orderid=get("orderid","i");
		 
		$row=MM("tutor","tutor_order")->selectRow("orderid=".$orderid);
		 
		if($row["shopid"]!=SHOPID){
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
	
	public function onAccept(){
		$orderid=get("orderid","i");
		$row=M("mod_tutor_order")->selectRow("orderid=".$orderid);
		if($row["shopid"]!=SHOPID){
			$this->goAll("暂无权限",1);
		}
		if($row["status"]!=0 || $row["ispay"]==0){
			$this->goAll("已处理",1);
		}
		M("mod_tutor_order")->update(array(
			"status"=>1
		),"orderid=".$orderid);
		
		M("mod_tutor_order_log")->insert(array(
			"userid"=>$row["userid"],
			"shopid"=>SHOPID,
			"orderid"=>$orderid,
			"createtime"=>date("Y-m-d H:i:s"),
			"content"=>"家教订单[".$orderid."]确认接单"
		));
		$this->goAll("接单成功");
		
	}
	
	public function onSend(){
		$orderid=get("orderid","i");
		$row=M("mod_tutor_order")->selectRow("orderid=".$orderid);
		if($row["shopid"]!=SHOPID){
			$this->goAll("暂无权限",1);
		}
		if($row["status"]!=1){
			$this->goAll("处理失败",1);
		}
		M("mod_tutor_order")->update(array(
			"status"=>2
		),"orderid=".$orderid);
		M("mod_tutor_order_log")->insert(array(
			"userid"=>$row["userid"],
			"shopid"=>SHOPID,
			"orderid"=>$orderid,
			"createtime"=>date("Y-m-d H:i:s"),
			"content"=>"家教订单[".$orderid."]到家对接中"
		));
		$this->goAll("对接中");
		
	}
	
	public function onFinish(){
		$orderid=get("orderid","i");
		$row=M("mod_tutor_order")->selectRow("orderid=".$orderid);
		if($row["shopid"]!=SHOPID){
			$this->goAll("暂无权限",1);
		}
		if($row["status"]>=3){
			$this->goAll("已处理",1);
		}
		MM("tutor","tutor_shop_money")->begin();
		M("mod_tutor_order")->update(array(
			"status"=>3
		),"orderid=".$orderid);
		M("mod_tutor_order_log")->insert(array(
			"userid"=>$row["userid"],
			"shopid"=>SHOPID,
			"orderid"=>$orderid,
			"createtime"=>date("Y-m-d H:i:s"),
			"content"=>"家教订单[".$orderid."]处理完成"
		));
		/**处理资金**/
		$tconfig=MM("tutor","tutor_config")->get();
		$money=$row["money"]*(100-$tconfig["per_money"])*0.01;
		MM("tutor","tutor_shop_money")->addMoney(array(
			"shopid"=>SHOPID,
			"income"=>$money,
			"balance"=>$money,
			"content"=>"家教订单[".$orderid."]处理完成，获得了".$money."元 "
		));
		MM("tutor","tutor_shop_money")->commit();
		$this->goAll("订单完成");
	}
	
	public function onCancel(){
		$orderid=get("orderid","i");
		$row=M("mod_tutor_order")->selectRow("orderid=".$orderid);
		if($row["shopid"]!=SHOPID){
			$this->goAll("暂无权限",1);
		}
		if($row["status"]>=3){
			$this->goAll("已处理",1);
		}
		M("mod_tutor_order")->update(array(
			"status"=>4
		),"orderid=".$orderid);
		M("mod_tutor_order_log")->insert(array(
			"userid"=>$row["userid"],
			"shopid"=>SHOPID,
			"orderid"=>$orderid,
			"createtime"=>date("Y-m-d H:i:s"),
			"content"=>"家教订单[".$orderid."]老师取消了"
		));
		$this->goAll("家教订单取消了");
	}
	
}
?>