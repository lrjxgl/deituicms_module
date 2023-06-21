<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class bzy_orderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=bzy_order&a=default";
			$limit=24;
			$start=get("per_page","i");
			$type=get("type","h");
			$typename="全部";
			switch($type){
				case "new":
					$where=" status=0   ";
					$typename="新订单";
					break;
				 
				case "unsend":
						$where=" status=1 ";
						$typename="待配送";
					break;
				case "unreceive":
						$where=" status=2 ";
						$typename="待收货";
					break;
				case "finish":
						$where=" status=3  ";
						$typename="已完成";
					break;
				case "unraty":
						$where=" status=3 AND israty=0 ";
						$typename="待评价";
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
			$list=M("mod_bzy_order")->select($option,$rscount);
			if($list){
				foreach($list as $v){
					$uids[]=$v["userid"];
					$pids[]=$v["productid"];
					$eids[]=$v["eventid"];
				}
				$us=M("user")->getUserByIds($uids);
				$pros=MM("bzy","bzy_product")->getListByIds($pids);
				$events=MM("bzy","bzy_event")->getListByIds($eids);
				foreach($list as $k=>$v){
					$v["nickname"]=$us[$v["userid"]]["nickname"];
					$v["user_head"]=$us[$v["userid"]]["user_head"];
					$v["product"]=$pros[$v["productid"]];
					$v["event"]=$events[$v["eventid"]];
					$v["time"]=date("H:i:s",$v["dateline"]);
					$v["status_name"]=MM("bzy","bzy_order")->getStatus($v);
					$list[$k]=$v;
					
				}
			}
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$list,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"typename"=>$typename
				)
			);
			$this->smarty->display("bzy_order/index.html");
		}
		
		public function onShow(){
			$orderid=get_post("orderid","i");
			$order=MM("bzy","bzy_order")->selectRow(array("where"=>"orderid=".$orderid));
			if(empty($order)){
				$this->goAll("数据出错",1);
			}
			$order["status_name"]=MM("bzy","bzy_order")->getStatus($order);
			$order["sendtype_name"]=$order["sendtype"]==1?'线下配送':'虚拟商品';
			$product=M("mod_bzy_product")->selectRow("productid=".$order["productid"]);
			$product["imgurl"]=images_site($product["imgurl"]);
			$this->smarty->goassign(array(
				"order"=>$order,
				"product"=>$product
			));
			$this->smarty->display("bzy_order/show.html");
		}
		public function onStatus(){
			$orderid=get_post('orderid',"i");
			$status=get_post("status","i");
			M("mod_bzy_order")->update(array("status"=>$status),"orderid=$orderid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$orderid=get_post('orderid',"i");
			M("mod_bzy_order")->update(array("status"=>11),"orderid=$orderid");
			$this->goAll("删除成功");
			 
		}
		
		public function onSend(){
			$orderid=get_post('orderid',"i");
			$order=M("mod_bzy_order")->selectRow(array("where"=>"orderid=".$orderid));
			if($order["status"]>=2){
				$this->goAll("已经处理过了",1);
			}
			M("mod_bzy_order")->update(array("status"=>2),"orderid=".$orderid);
			 
			$this->goall("发货成功");
		}
		
	}

?>