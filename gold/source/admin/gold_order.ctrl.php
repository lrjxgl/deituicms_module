<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class gold_orderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=gold_order&a=default";
			$limit=20;
			$start=get("per_page","i");
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
			$data=M("mod_gold_order")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$ids[]=$v["productid"];
				}
				$statusList=MM("gold","gold_order")->statusList;
				$pros=MM("gold","gold_product")->getListByIds($ids);
				 
				foreach($data as $k=>$v){
					$v["title"]=$pros[$v["productid"]]["title"];
					$v["imgurl"]=$pros[$v["productid"]]["imgurl"];
					$v["status_name"]=$statusList[$v["status"]];
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
			$this->smarty->display("gold_order/index.html");
		}
		
		public function onShow(){
			$orderid=get_post("orderid","i");
			$order=M("mod_gold_order")->selectRow(array("where"=>"orderid=".$orderid));
			$product=M("mod_gold_product")->selectRow("id=".$order["productid"]);
			$product["imgurl"]=images_site($product["imgurl"]);
			$statusList=MM("gold","gold_order")->statusList;
			$order["status_name"]=$statusList[$order["status"]];
			$this->smarty->goassign(array(
				"order"=>$order,
				"product"=>$product
			));
			$this->smarty->display("gold_order/show.html");
		}
		
		public function onConfirm(){
				$orderid=get_post("orderid","i");
				$order=MM("gold","gold_order")->selectRow("orderid=".$orderid);
				
				if($order["status"]!=0){
					$this->goAll("该订单已经处理了",1);
				}
				MM("gold","gold_order")->begin();
				MM("gold","gold_order")->update(array(
					"status"=>1
				),"orderid=".$orderid);
				//处理日志
				$content=post("content","h");
				$adminid=$_SESSION["ssadmin"]["adminid"];
				MM("gold","gold_order_log")->insert(array(
					"adminid"=>$adminid,
					"orderid"=>$orderid,
					"createtime"=>date("Y-m-d H:i:s"),
					"content"=>$content
				));
				
				MM("gold","gold_order")->commit();
				$this->goAll("确认接单成功");
			}
			
			/**取消**/
			public function onCancel(){
				$orderid=get_post("orderid","i");
				$order=MM("gold","gold_order")->selectRow("orderid=".$orderid);
				if($order["status"]!=0){
					$this->goAll("该订单已经处理了",1);
				} 
				MM("gold","gold_order")->begin();
				MM("gold","gold_order")->update(array(
					"status"=>10
				),"orderid=".$orderid);
				M("user")->addMoney(array(
					"userid"=>$order["userid"],
					"gold"=>$order["gold"],
					"content"=>"您的兑换订单被取消了，退回{$order["gold"]}个金币"
				));
				 M("mod_gold_product")->changenum("total_num",1,"id=".$order["productid"]);
				//处理日志
				$content=post("content","h");
				$adminid=$_SESSION["ssadmin"]["adminid"];
				MM("gold","gold_order_log")->insert(array(
					"adminid"=>$adminid,
					"orderid"=>$orderid,
					"createtime"=>date("Y-m-d H:i:s"),
					"content"=>$content
				));
				 
				MM("gold","gold_order")->commit();
				$this->goAll("取消成功");
			}
			
			public function onSend(){
				$orderid=get_post("orderid","i");
				$order=MM("gold","gold_order")->selectRow("orderid=".$orderid);
				$express_no=post("express_no","h");
				 
				if($order["status"]>=2){
					$this->goAll("该订单已经处理了",1);
				}
				MM("gold","gold_order")->update(array(
					"status"=>2,
					"express_no"=>$express_no
				),"orderid=".$orderid);
				//处理日志
				$content=post("content","h");
				$adminid=$_SESSION["ssadmin"]["adminid"];
				MM("gold","gold_order_log")->insert(array(
					"adminid"=>$adminid,
					"orderid"=>$orderid,
					"createtime"=>date("Y-m-d H:i:s"),
					"content"=>$content
				));
				$this->goAll("发货成功");
			}
		
		
	}

?>