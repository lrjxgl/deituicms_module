<?php
class dzp_eventControl extends skymvc{
	public $gonum=0;
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		
		$this->smarty->display("dzp_event/index.html");
	}
	
	public function onShow(){
		$userid=M("login")->userid;
		$eventid=get("eventid","i");
		$event=M("mod_dzp_event")->selectRow("eventid=".$eventid);
		if($event["status"]!=1){
			$this->goAll("活动已下线",1);
		}
		$list=M("mod_dzp_product")->select(array(
			"where"=>" eventid=".$eventid." AND status=1 ",
			"order"=>"orderindex ASC",
			"limit"=>8
		));
		$auth=M("user_auth")->selectRow("userid=".$userid." AND status=1 ");
		$isauth=false;
		if(!empty($auth)){
			$isauth=true;
		}
		$this->smarty->goAssign(array(
			"event"=>$event,
			"list"=>$list,
			"isauth"=>$isauth
		));
		$tpl="dzp_event/show.html";
		if($event["tpl"]){
			$tpl=$event["tpl"];
		}
		$this->smarty->display($tpl);
		
	}
	
	public function onGetIndex($reorder=false){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		
		$eventid=get("eventid","i");
		$event=M("mod_dzp_event")->selectRow("eventid=".$eventid);
		if($event["etime"]<time()|| $event["stime"]>time()){
			$this->goAll("活动已经结束",1);
		}
		if($event["status"]!=1){
			$this->goAll("活动已经结束",1);
		}
		//检测是否关注微信公众
		if(!M("weixin")->checkFollow($userid)){
			$this->goAll("请先关注公众号",1);
		}
		//检测区域限制
		if(!empty($event["addronly"])){
			$auth=M("user_auth")->selectRow("userid=".$userid." AND status=1 ");
			if(empty($auth) || false===strpos($auth["address"],$event["addronly"])){
				$this->goAll("当前活动限制在".$event["addronly"],1);
			}
		}
		
		$rs=M("mod_dzp_product")->selectRow(array(
			"where"=>" eventid=".$eventid." AND status=1 AND max_num>sold_num "
		));
		if(empty($rs)){
			$this->goAll("所有奖品已经被领取了",1);
		}
		$join=M("mod_dzp_event_join")->selectRow("userid=".$userid." AND eventid=".$eventid);
		if(!$join){
			M("mod_dzp_event_join")->insert(array(
				"userid"=>$userid,
				"max_num"=>$event["limit_num"],
				"eventid"=>$eventid,
				"dateline"=>time()
			));
			$join=M("mod_dzp_event_join")->selectRow("userid=".$userid." AND eventid=".$eventid);
			M("mod_dzp_event")->changenum("join_num",1,"eventid=".$eventid);
		}
		if(!$reorder){
			if($join["max_num"]<=$join["use_num"]){
				$this->goAll("参与次数用完了",1);
			}
			M("mod_dzp_event_join")->update(array(
				"use_num"=>$join["use_num"]+1
			),"id=".$join["id"]);
		}
		
		$list=M("mod_dzp_product")->select(array(
			"where"=>" eventid=".$eventid." AND status=1 ",
			"order"=>"orderindex ASC",
			"limit"=>8
		));
		//设置中奖
		$num=rand(0,$event["gailv"]);
		$index=0;
		$step=0;
		foreach($list as $key=>$v){
			//$arr=explode("-",$v["gailv"]);
			$arr=array($step,$step+$v["gailv"]);
			$step+=$v["gailv"];
			if($num>$arr[0] && $num<=$arr[1]){
				$index=$key;
				break;
			}
		}
		$product=$list[$index];
		if($product["sold_num"]>=$product["max_num"]){
			$this->gonum++;
			if($this->gonum>4){
				$pid=M("mod_dzp_product")->selectOne(array(
					"where"=>"isorder=0 AND status=1 AND eventid=".$eventid,
					"fields"=>"productid"
				));
				$index=0;
				foreach($list as $key=>$v){
					if($pid==$v["productid"]){
						$index=$key;
						break;
					}
				}
				$product=$list[$index];
				$this->goAll("success",0,array(
					"index"=>$index,
					"num"=>$num,
					"gonum"=>$this->gonum,
					"product"=>$product
				));
			}
			$this->onGetIndex(true);
			return false;
		}
		if($product["isorder"]){
			M("mod_dzp_product")->update(array(
				"sold_num"=>$product["sold_num"]+1
			),"productid=".$product["productid"]);
		}
		
		M("mod_dzp_event_log")->insert(array(
			"userid"=>$userid,
			"productid"=>$product["productid"],
			"eventid"=>$eventid,
			"dateline"=>time()
		));
		
		if($product["isorder"]){
			switch($product["ptype"]){
				case "gold":
					$status=3;
					M("user")->addMoney(array(
						"userid"=>$userid,
						"gold"=>$product["reward_num"],
						"content"=>"大转盘获得了".$product["reward_num"]."个金币"
					));
					break;
			}
			M("mod_dzp_order")->insert(array(
				"userid"=>$userid,
				"productid"=>$product["productid"],
				"eventid"=>$eventid,
				"dateline"=>time(),
				"sendtype"=>$product["sendtype"],
				"reward_num"=>$product["reward_num"],
				"status"=>$status
			));
			
		}
		$this->goAll("success",0,array(
			"index"=>$index,
			"num"=>$num,
			"product"=>$product
		));
		
	}
	
	public function onOrder(){
		$eventid=get("eventid","i");
		$event=M("mod_dzp_event")->selectRow("eventid=".$eventid);
		
		$list=M("mod_dzp_order")->select(array(
			"where"=>" eventid=".$eventid."   ",
			"limit"=>100,
			"order"=>"orderid DESC"
		));
		if($list){
			foreach($list as $v){
				$uids[]=$v["userid"];
				$pids[]=$v["productid"];
			}
			$us=M("user")->getUserByIds($uids);
			$pros=MM("dzp","dzp_product")->getListByIds($pids);
			foreach($list as $k=>$v){
				$v["nickname"]=$us[$v["userid"]]["nickname"];
				$v["user_head"]=$us[$v["userid"]]["user_head"];
				$v["product"]=$pros[$v["productid"]];
				$v["time"]=date("H:i:s",$v["dateline"]);
				$list[$k]=$v;
				
			}
		}
		$this->smarty->goAssign(array(
			"event"=>$event,
			"list"=>$list
		));
	}
	
	public function onMyOrder(){
		$userid=M("login")->userid;
		M("login")->checkLogin();
		$eventid=get("eventid","i");
		$event=M("mod_dzp_event")->selectRow("eventid=".$eventid);
		
		$list=M("mod_dzp_order")->select(array(
			"where"=>" eventid=".$eventid." AND userid=".$userid,
			"limit"=>100,
			"order"=>"orderid DESC"
		));
		if($list){
			foreach($list as $v){
				$uids[]=$v["userid"];
				$pids[]=$v["productid"];
			}
			$us=M("user")->getUserByIds($uids);
			$pros=MM("dzp","dzp_product")->getListByIds($pids);
			foreach($list as $k=>$v){
				$v["nickname"]=$us[$v["userid"]]["nickname"];
				$v["user_head"]=$us[$v["userid"]]["user_head"];
				$v["product"]=$pros[$v["productid"]];
				$v["time"]=date("H:i:s",$v["dateline"]);
				$list[$k]=$v;
				
			}
		}
		$this->smarty->goAssign(array(
			 
			"list"=>$list
		));
	}
}
?>