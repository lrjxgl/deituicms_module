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
		public function onInit(){
			M("login")->checkLogin();
		}
		public function onDefault(){
			
		}
		public function onMy(){
			$userid=M("login")->userid;
			$where=" userid=".$userid." AND status in(0,1,2,3)";
			$url="/module.php?m=bzy_order&a=my";
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
					$v["time"]=substr($v["createtime"],0,10);
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
			$this->smarty->display("bzy_order/my.html");
		}
		
		public function onShow(){
			$orderid=get_post("orderid","i");
			$data=M("mod_bzy_order")->selectRow(array("where"=>"orderid=".$orderid));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("bzy_order/show.html");
		}
		public function onChangeAddr(){
			$userid=M("login")->userid;
			$orderid=get_post('orderid',"i");
			$row=M("mod_bzy_order")->selectRow("orderid=".$orderid);
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
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
			M("mod_bzy_order")->update(array(
				"address"=>$address,
				"nickname"=>$nickname,
				"telephone"=>$telephone
				
			),"orderid=$orderid");
			$this->goall("地址修改成功");
		}
		
		 
		
		public function onReceived(){
			$userid=M("login")->userid;
			$orderid=get_post('orderid',"i");
			 
			$order=M("mod_bzy_order")->selectRow(array("where"=>"orderid=".$orderid));
			if($order["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			if($order["status"]!=2){
				$this->goAll("该订单还没发货",1);
			}
			M("mod_bzy_order")->update(array("status"=>3),"orderid=".$orderid);
			 
			$this->goall("订单完成");
		}
		
		
		
	}

?>