<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class olprint_orderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=SITEWHERE." status in(0,1,2)";
			$url="/moduleadmin.php?m=olprint_order&a=default";
			$limit=20;
			$start=get("per_page","i");
			$type=get("type","h");
			$tabname="新订单";
			switch(get('type')){
				
				case "unraty":
					$url.="&type=unraty";
					$where.="   AND status=3 AND israty=0";
					$tabname="待评价";
					break;
				case "unpay":
					$url.="&type=unpay";
					$where.=" AND status=0 AND ispay=0 ";
					$tabname="待支付";
					break;
				case "unsend":
					$url.="&type=unsend";
					$where.=" AND status=1 AND ispay=1 ";
					$tabname="待发货";
					break;	
				case "unreceive":
					$url.="&type=unreceive";
					$where.=" AND status =2  ";
					$tabname="待收货";
					break;
				case "all":
					$type="all";
					$where.=" AND status in(0,1,2,3,4)";
					$tabname="全部订单";
					break;
				default:
					$url.="&type=unsend";
					$where.=" AND status=0 AND ispay=1 ";
					$type="new";
					$tabname="新订单";
					break;
				
			}
			$orderid=get("orderid","i");
			if($orderid){
				$where="  orderid=".$orderid;
			}
			$stime=get('stime','h');
			if($stime){
				$where.=" AND createtime>='".$stime."' ";
			}
			$etime=get('etime','h');
			if($etime){
				$where.=" AND createtime<='".$etime."'";
			}
			 
			$nickname=get("nickname","h");
			if($nickname){
				$user=M("user")->selectRow("nickname='".$nickname."'");
				if($user){
					$where.=" AND userid=".$user["userid"];
				}else{
					$where=" 1=2 ";
				}
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
					"url"=>$url,
					"tabname"=>$tabname
				)
			);
			$this->smarty->display("olprint_order/index.html");
		}
		
		public function onShow(){
			$orderid=get_post("orderid","i");
			$data=M("mod_olprint_order")->selectRow(array("where"=>"orderid=".$orderid));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("olprint_order/show.html");
		}
		public function onStatus(){
			$orderid=get_post('orderid',"i");
			$status=get_post("status","i");
			M("mod_olprint_order")->update(array("status"=>$status),"orderid=$orderid");
			$this->goall("状态修改成功");
		}
		
		public function onCancel(){
			$orderid=get("orderid","i");
			$order=MM("olprint","olprint_order")->selectRow("orderid=".$orderid);
			 
			if($order["status"]!=0){
				$this->goAll("订单无法取消",1);
			}
			MM("olprint","olprint_order")->begin();
			MM("olprint","olprint_order")->update(array(
				"status"=>4
			),"orderid=".$orderid);
			if($order["ispay"]==1){
				//退款到原账户
				if($order['recharge_id']){
					$recharge=M("recharge")->selectRow("id=".$order['recharge_id']);
					$odata=array(
						"tablename"=>"mod_olprint_order",
						"userid"=>$recharge['userid'],
						"money"=>$recharge['money'],
						"createtime"=>$recharge['createtime'],
						"recharge_orderno"=>$recharge['orderno'],
						"recharge_pay_orderno"=>$recharge['pay_orderno'],
						"recharge_id"=>$order['recharge_id'],
					);
					M("refund_apply")->insert(array(
						"userid"=>$order['userid'],
						 
						"paytype"=>$recharge['pay_type'],
						"createtime"=>date("Y-m-d H:i:s"),
						"recharge_orderno"=>$recharge['orderno'],
						"recharge_pay_orderno"=>$recharge['pay_orderno'],
						"money"=>$order['money'],
						"recharge_id"=>$order['recharge_id'],
						"content"=>"olprint订单取消，申请退回支付渠道",
						"odata"=>base64_encode(json_encode($odata))
					));
				}else{
					M("user")->addMoney(array(
						"userid"=>$order["userid"],
						"money"=>$order["money"],
						"content"=>"商家取消打印订单，退回".$order["money"]."元"
					));
				}
				
			}
			
			
			MM("olprint","olprint_order")->commit();
			MM("olprint","olprint_shop_notice")->sendUpdateOrder($orderid,"user");
			$this->goAll("订单取消成功");
		}
		
	}

?>