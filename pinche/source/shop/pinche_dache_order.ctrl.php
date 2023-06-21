<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class pinche_dache_orderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" driverid=".DRIVERID;
			$url="/moduleadmin.php?m=pinche_dache_order&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_pinche_dache_order")->select($option,$rscount);
			$statusList=MM("pinche","pinche_dache_order")->statusList();
			if(!empty($data)){
				$uids=[];
				foreach($data as $v){
					$uids[]=$v["userid"];
				}
				$us=M("user")->getUserByIds($uids);
				foreach($data as $k=>$v){
					$v["nickname"]=$us[$v["userid"]]["nickname"];
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
			$this->smarty->display("pinche_dache_order/index.html");
		}
		public function onNew(){
			$where=" status=0 AND driverid=0 ";
			$url="/moduleadmin.php?m=pinche_dache_order&a=new";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("pinche","pinche_dache_order")->select($option,$rscount);
			$statusList=MM("pinche","pinche_dache_order")->statusList();
			if(!empty($data)){
				$uids=[];
				foreach($data as $v){
					$uids[]=$v["userid"];
				}
				$us=M("user")->getUserByIds($uids);
				foreach($data as $k=>$v){
					$v["nickname"]=$us[$v["userid"]]["nickname"];
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
			$this->smarty->display("pinche_dache_order/new.html");
		}
		public function onShow(){
			$orderid=get_post("orderid","i");
			$order=M("mod_pinche_dache_order")->selectRow(array("where"=>"orderid=".$orderid));
			$statusList=MM("pinche","pinche_dache_order")->statusList();
			$order["status_name"]=$statusList[$order["status"]];
			$this->smarty->goassign(array(
				"order"=>$order
			));
			$this->smarty->display("pinche_dache_order/show.html");
		}
		 
		public function onGrabOrder(){
			$orderid=get("orderid","i");
			$order=M("mod_pinche_dache_order")->selectRow("orderid=".$orderid);
			if(empty($order)){
				$this->goAll("订单不存在",1);
			}
			if($order["driverid"]){
				$this->goAll("订单已经被抢了",1);
			}
			M("mod_pinche_dache_order")->update(array(
				"driverid"=>DRIVERID,
				"status"=>1,
				"order_time"=>date("Y-m-d H:i:s")
			),"orderid=".$orderid);
			$this->goAll("抢单成功");
		}
		
		public function onSend(){
			$orderid=get("orderid","i");
			$order=M("mod_pinche_dache_order")->selectRow("orderid=".$orderid);
			if(empty($order) || $order["status"]>2){
				$this->goAll("订单无法完成",1);
			}
			if($order["driverid"]!=DRIVERID){
				$this->goAll("暂无权限",1);
			}
			M("mod_pinche_dache_order")->update(array(
				 
				"status"=>2,
			 
			),"orderid=".$orderid);
			$this->goAll("配送成功");
		}
		
	}

?>