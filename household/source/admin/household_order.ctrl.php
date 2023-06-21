<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class household_orderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2,3,4)";
			$url="/moduleadmin.php?m=household_order&a=default";
			$limit=24;
			$start=get("per_page","i");
			$type=get("type","h");
			switch($type){
				case "new":
					$where=" status=0 AND ispay=1 AND senderid=0 ";
					break;
				case "unpay":
					$where=" status=0 AND ispay=0 ";
					break;
				case "unsend":
						$where=" status=1 ";
					break;
				case "unreceive":
						$where=" status=2 ";
					break;
				case "unraty":
						$where=" status=3 AND israty=0 ";
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
			$orderno=get("orderno","h");
			if($orderno){
				$where.=" AND orderno ='".$orderno."' ";
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
			$data=M("mod_household_order")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$oids[]=$v["orderid"];
					$uids[]=$v["userid"];
					$sids[]=$v["senderid"];
				}
				$ods=MM("household","household_order_data")->getListByOrderIds($oids);
				$us=M("user")->getUserByIds($uids);
				$sds=MM("household","household_sender")->getListByIds($sids);
				foreach($data as $k=>$v){
					$v['addr']=$ods[$v['orderid']]['address'];
					$v['prolist']=$ods[$v['orderid']]['prolist'];
					$v["status_name"]=MM("household","household_order")->getStatus($v);
					$v["timeago"]=timeago(strtotime($v["createtime"]));
					$v["nickname"]=$us[$v["userid"]]["nickname"];
					$v["status_name"]=MM("household","household_order")->getStatus($v);
					$v["sender"]=$v["senderid"]?$sds[$v["senderid"]]:[];
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
			$this->smarty->display("household_order/index.html");
		}
		
		public function onShow(){
			$orderid=get("orderid","i");
			$order=MM("household","household_order")->selectRow("orderid=".$orderid);
			$orderdata=MM("household","household_order_data")->get($orderid);
			 
			$order["status_name"]=MM("household","household_order")->getStatus($order);
			$sender=MM("household","household_sender")->selectRow("senderid=".$order["senderid"]);
			$logList=MM("household","household_order_log")->select(array(
				"where"=>"orderid=".$orderid,
				"order"=>"id ASC"
			));
			$this->smarty->goAssign(array(
				"order"=>$order,
				"addr"=>$orderdata["address"],
				"prolist"=>$orderdata["prolist"],
				"sender"=>$sender,
				"logList"=>$logList
			));
			$this->smarty->display("household_order/show.html");
		}
		
		public function onConfirm(){
			$orderid=get_post("orderid","i");
			$order=MM("household","household_order")->selectRow("orderid=".$orderid);
			if($order["ispay"]==0){
				$this->goAll("该订单还未支付",1);
			}
			if($order["status"]!=0){
				$this->goAll("该订单已经处理了",1);
			}
			MM("household","household_order")->begin();
			MM("household","household_order")->update(array(
				"status"=>1
			),"orderid=".$orderid);
			//处理日志
			$content=post("content","h");
			$adminid=$_SESSION["ssadmin"]["adminid"];
			MM("household","household_order_log")->insert(array(
				"adminid"=>$adminid,
				"orderid"=>$orderid,
				"createtime"=>date("Y-m-d H:i:s"),
				"content"=>$content
			));
			//发送消息
			M("notice")->add(array(
				"title"=>"订单处理",
				"userid"=>$order["userid"],
				"linkurl"=>array(
					"path"=>"/index.php",
					"m"=>"household_order",
					"a"=>"show",
					"param"=>"orderid=".$orderid
				),
				"template_id"=>"updateorder",
				"content"=>array(
					"content"=>"您的订单已确认，马上发货",
					"first"=>"您的订单已确认，马上发货",
					"keyword1"=>$orderno,
					"keyword2"=>"已确认",
					"DATA"=>"感谢您的支持"
				)
			));
			MM("household","household_order")->commit();
			$this->goAll("确认接单成功");
		}
		
		/**取消**/
		public function onCancel(){
			$orderid=get_post("orderid","i");
			$order=MM("household","household_order")->selectRow("orderid=".$orderid);
			if($order["status"]!=0){
				$this->goAll("该订单已经处理了",1);
			} 
			MM("household","household_order")->begin();
			MM("household","household_order")->update(array(
				"status"=>10
			),"orderid=".$orderid);
			if($order["ispay"]==1){
				//退款到原账户
				$recharge=M("recharge")->selectRow("id=".$order['recharge_id']);
				$odata=array(
					"tablename"=>"mod_household_order",
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
					"content"=>"household订单取消，申请退回支付渠道",
					"odata"=>base64_encode(json_encode($odata))
				));
			}
			//处理日志
			$content=post("content","h");
			$adminid=$_SESSION["ssadmin"]["adminid"];
			MM("household","household_order_log")->insert(array(
				"adminid"=>$adminid,
				"orderid"=>$orderid,
				"createtime"=>date("Y-m-d H:i:s"),
				"content"=>$content
			));
			//增加商品库存
			$proList=MM("household","household_order_product")->select(array(
				"where"=>"orderid=".$orderid
			));
			if($proList){
				foreach($proList as $v){
					if($v["ksid"]){
						MM("household","household_product_ks")->changenum("total_num",$v["amount"],"id=".$v["ksid"]);
					}else{
						MM("household","household_product")->changenum("total_num",$v["amount"],"id=".$v["productid"]);
					}	
				}
			}
			MM("household","household_order")->commit();
			$this->goAll("取消成功");
		}
		
		public function onSend(){
			$orderid=get_post("orderid","i");
			$order=MM("household","household_order")->selectRow("orderid=".$orderid);
			$express_no=post("express_no","h");
			if($order["ispay"]==0){
				$this->goAll("该订单还未支付",1);
			}
			if($order["status"]>=2){
				$this->goAll("该订单已经处理了",1);
			}
			MM("household","household_order")->update(array(
				"status"=>2,
				"express_no"=>$express_no
			),"orderid=".$orderid);
			//处理日志
			$content=post("content","h");
			$adminid=$_SESSION["ssadmin"]["adminid"];
			MM("household","household_order_log")->insert(array(
				"adminid"=>$adminid,
				"orderid"=>$orderid,
				"createtime"=>date("Y-m-d H:i:s"),
				"content"=>$content
			));
			$this->goAll("发货成功");
		}
		
		public function onPaidan(){
			$orderid=get_post("orderid","i");
			$order=MM("household","household_order")->selectRow("orderid=".$orderid);
			if($order["senderid"]){
				$this->goAll("已经派单了",1);
			}
			$express_no=post("express_no","h");
			if($order["ispay"]==0){
				$this->goAll("该订单还未支付",1);
			}
			if($order["status"]>=2){
				$this->goAll("该订单已经处理了",1);
			}
			$senderid=post("senderid","i");
			$sender=MM("household","household_sender")->selectRow("senderid=".$senderid);
			if(empty($sender)){
				$this->goALl("师傅不存在",1);
			}
			MM("household","household_order")->update(array(
				
				"senderid"=>$senderid,
				"express_no"=>$express_no
			),"orderid=".$orderid);
			//处理日志
			$content="平台派单给:".$sender["truename"];
			$adminid=$_SESSION["ssadmin"]["adminid"];
			MM("household","household_order_log")->insert(array(
				"adminid"=>$adminid,
				"orderid"=>$orderid,
				"createtime"=>date("Y-m-d H:i:s"),
				"content"=>$content
			));
			$this->goAll("派单成功");
		}
		
	}

?>