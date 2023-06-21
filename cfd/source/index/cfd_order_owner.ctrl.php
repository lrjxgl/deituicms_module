<?php 
class cfd_order_ownerControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$cfdid=get("cfdid","i");
		$cfd=M("mod_cfd")->selectRow(array(
			"where"=>" cfdid=".$cfdid,
			"fields"=>"cfdid,title,userid"
		));
		$userid=M("login")->userid;
		if($cfd["userid"]!=$userid){
			$this->goAll("无权管理",1);
		}
		$where=" cfdid=".$cfdid;
		$type=get("type","h");
		switch($type){
			case "unpay":
				$where.=" AND ispay=0 AND status=0 ";
				break;
			case "unfinish":
				$where.=" AND ispay=1 AND isreward=0 ";
				break;
			case "finish":
				$where.=" AND ispay=1 AND isreward=1 ";
				break;
			default:
				$where.=" AND status in(0,1,2,3,4) ";
				break;
				
		}
		$option=array(
			"where"=>$where,
			"order"=>"orderid DESC"
		);
		$data=M("mod_cfd_order")->select($option);
		if($data){
			foreach($data as $v){
				 
				$rewardids[]=$v['rewardid'];
			}
			 
			$rewards=MM("cfd","cfd_reward")->getListByIds($rewardids);
			 
			foreach($data as $k=>$v){
				 
				$v['reward']=$rewards[$v['rewardid']];
				if($v["ispay"]==0){
					$v["status_name"]="待支付";
				}elseif($v["isreward"]==1){
					$v["status_name"]="已回报";
				}else{
					$v["status_name"]="待回报";
				}
				
				$data[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"data"=>$data,
			"cfd"=>$cfd
		));
		
		$this->smarty->display("cfd_order_owner/index.html");
	}
	
	public function onShow(){
		$orderid=get("orderid","i");
		$order=M("mod_cfd_order")->selectRow("orderid=".$orderid);
		$cfdid=$order["cfdid"];
		$cfd=M("mod_cfd")->selectRow(array(
			"where"=>" cfdid=".$cfdid 
		));
		$userid=M("login")->userid;
		if($cfd["userid"]!=$userid){
			$this->goAll("无权管理",1);
		}
		$reward=M("mod_cfd_reward")->selectRow("id=".$order["rewardid"]);
		if($order["ispay"]==0){
			$order["status_name"]="待支付";
		}elseif($order["isreward"]==1){
			$order["status_name"]="已回报";
		}else{
			$order["status_name"]="待回报";
		}
		$this->smarty->goAssign(array(
			"order"=>$order,
			"cfd"=>$cfd,
			"reward"=>$reward
		));
		$this->smarty->display("cfd_order_owner/show.html");
	}
	
	public function onReward(){
		$orderid=post("orderid","i");
		$reward_content=post("reward_content","h");
		$order=M("mod_cfd_order")->selectRow("orderid=".$orderid);
		$cfdid=$order["cfdid"];
		$cfd=M("mod_cfd")->selectRow(array(
			"where"=>" cfdid=".$cfdid 
		));
		$userid=M("login")->userid;
		if($cfd["userid"]!=$userid){
			$this->goAll("无权管理",1);
		}
		M("mod_cfd_order")->update(array(
			"isreward"=>1,
			"reward_content"=>$reward_content,
			"status"=>3
		),"orderid=".$orderid);
		$this->goALl("success");
		
	}
	
}