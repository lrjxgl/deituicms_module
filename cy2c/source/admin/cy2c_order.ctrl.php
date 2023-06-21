<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class cy2c_orderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2,3,4)";
			$url="/moduleadmin.php?m=cy2c_order&a=default";
			$limit=24;
			$start=get("per_page","i");
			$type=get("type","h");
			$type_name="全部订单";
			switch($type){
				case "new":
					$where=" status=0 AND ispay=1 ";
					$type_name="新订单";
					break;
				case "unpay":
					$where=" status=0 AND ispay=0 ";
					$type_name="待支付";
					break;
				case "unsend":
						$where=" status=1 ";
						$type_name="制作中";
					break;
				case "unreceive":
						$where=" status=2 ";
						$type_name="待收货";
					break;
				case "unraty":
						$where=" status=3 AND israty=0 ";
						$type_name="待评价";
					break;
				case "finish":
						$where=" status=3  ";
						$type_name="已完成";
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
			$data=M("mod_cy2c_order")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$uids[]=$v["userid"];
					$placeids[]=$v["placeid"];
				}
				$us=M("user")->getUserByIds($uids);
				$places=MM("cy2c","cy2c_place")->getListByIds($placeids);
				foreach($data as $k=>$v){
					$v["place"]=$places[$v["placeid"]];
					$v["nickname"]=$us[$v["userid"]]["nickname"];
					$v["status_name"]=MM("cy2c","cy2c_order")->getStatus($v);
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
					"type_name"=>$type_name,
					"type"=>$type
				)
			);
			$this->smarty->display("cy2c_order/index.html");
		}
		
		public function onShow(){
			$orderid=get("orderid","i");
			$order=MM("cy2c","cy2c_order")->selectRow("orderid=".$orderid);
			$orderdata=MM("cy2c","cy2c_order_data")->get($orderid,true);
			 
			$order["status_name"]=MM("cy2c","cy2c_order")->getStatus($order);
			if($order["placeid"]){
				$place=MM("cy2c","cy2c_place")->selectRow("placeid=".$order["placeid"]);
			}
			$this->smarty->goAssign(array(
				"order"=>$order,
				"place"=>$place,
				"addr"=>$orderdata["address"],
				"prolist"=>$orderdata["prolist"]
			));
			$this->smarty->display("cy2c_order/show.html");
		}
		
		public function onConfirm(){
			$orderid=get_post("orderid","i");
			$order=MM("cy2c","cy2c_order")->selectRow("orderid=".$orderid);
			if($order["ispay"]==0){
				$this->goAll("该订单还未支付",1);
			}
			if($order["status"]!=0){
				$this->goAll("该订单已经处理了",1);
			}
			MM("cy2c","cy2c_order")->begin();
			MM("cy2c","cy2c_order")->update(array(
				"status"=>1
			),"orderid=".$orderid);
			MM("cy2c","cy2c_order_product")->update(array(
				"status"=>1
			),"orderid=".$orderid);
			//处理日志
			$content=post("content","h");
			$adminid=$_SESSION["ssadmin"]["adminid"];
			MM("cy2c","cy2c_order_log")->insert(array(
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
					"m"=>"cy2c_order",
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
			MM("cy2c","cy2c_order")->commit();
			$this->goAll("确认接单成功");
		}
		
		/**取消**/
		public function onCancel(){
			$orderid=get_post("orderid","i");
			$order=MM("cy2c","cy2c_order")->selectRow("orderid=".$orderid);
			if($order["status"]!=0){
				$this->goAll("该订单已经处理了",1);
			} 
			MM("cy2c","cy2c_order")->begin();
			MM("cy2c","cy2c_order")->update(array(
				"status"=>10
			),"orderid=".$orderid);
			if($order["ispay"]==1){
				//退款到原账户
				$recharge=M("recharge")->selectRow("id=".$order['recharge_id']);
				$odata=array(
					"tablename"=>"mod_cy2c_order",
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
					"content"=>"cy2c订单取消，申请退回支付渠道",
					"odata"=>base64_encode(json_encode($odata))
				));
			}
			//处理日志
			$content=post("content","h");
			$adminid=$_SESSION["ssadmin"]["adminid"];
			MM("cy2c","cy2c_order_log")->insert(array(
				"adminid"=>$adminid,
				"orderid"=>$orderid,
				"createtime"=>date("Y-m-d H:i:s"),
				"content"=>$content
			));
			//增加商品库存
			$proList=MM("cy2c","cy2c_order_product")->select(array(
				"where"=>"orderid=".$orderid
			));
			if($proList){
				foreach($proList as $v){
					if($v["ksid"]){
						MM("cy2c","cy2c_product_ks")->changenum("total_num",$v["amount"],"id=".$v["ksid"]);
					}else{
						MM("cy2c","cy2c_product")->changenum("total_num",$v["amount"],"id=".$v["productid"]);
					}	
				}
			}
			MM("cy2c","cy2c_order_product")->update(array(
				"status"=>8
			),"orderid=".$orderid);
			MM("cy2c","cy2c_order")->commit();
			$this->goAll("取消成功");
		}
		
		public function onSend(){
			$orderid=get_post("orderid","i");
			$order=MM("cy2c","cy2c_order")->selectRow("orderid=".$orderid);
			$express_no=post("express_no","h");
			if($order["ispay"]==0){
				$this->goAll("该订单还未支付",1);
			}
			if($order["status"]>=2){
				$this->goAll("该订单已经处理了",1);
			}
			MM("cy2c","cy2c_order")->update(array(
				"status"=>2,
				"express_no"=>$express_no
			),"orderid=".$orderid);
			//处理日志
			$content=post("content","h");
			$adminid=$_SESSION["ssadmin"]["adminid"];
			MM("cy2c","cy2c_order_log")->insert(array(
				"adminid"=>$adminid,
				"orderid"=>$orderid,
				"createtime"=>date("Y-m-d H:i:s"),
				"content"=>$content
			));
			$this->goAll("发货成功");
		}
		
		public function onFinish(){
			$orderid=get_post("orderid","i");
			MM("cy2c","cy2c_order")->update(array(
				"status"=>3,
				"isreceived"=>1
			),"orderid=".$orderid);
			$this->goAll("success");
		}
		
	}

?>