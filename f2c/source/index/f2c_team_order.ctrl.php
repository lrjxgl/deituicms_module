<?php
class f2c_team_orderControl extends skymvc{
	public $team;
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$team=MM("f2c","f2c_team")->selectRow("userid=".$userid);
		if(empty($team)){
			$this->goAll("您还不是团长",1);
		}
		$this->team=$team;
	}
	public function onDefault(){
		$type=get("type","h");
		$this->smarty->goAssign(array(
			"type"=>$type
		));
		$this->smarty->display("f2c_team_order/index.html");
	}
	 
	public function onList(){
		
		$where=" teamid=".$this->team["teamid"];
		$url="/module.php?m=f2c_order&a=my";
		$limit=24;
		$start=get("per_page","i");
		$type=get("type","h");
		switch(get('type')){
			case "unraty":
				$url.="&type=unraty";
				$where.="   AND isreceived=1 AND israty=0";
				break;
			case "unpay":
				$url.="&type=unpay";
				$where.=" AND status=0 AND ispay=0 ";
				break;
			case "unsend":
				$url.="&type=unsend";
				$where.=" AND status in(0,1) AND ispay=1 ";
				break;	
			case "unreceive":
				$url.="&type=unreceive";
				$where.=" AND status =2 AND ispay=1 AND isreceived=0 ";
				break;
			default:
				$type="all";
				$where.=" AND status in(0,1,2,3)";
				break;
			
		}
		 
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" orderid DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=MM("f2c","f2c_order")->select($option,$rscount);
		if($data){
			foreach($data as $v){
				$oids[]=$v["orderid"];
			}
			$ods=MM("f2c","f2c_order_data")->getListByOrderIds($oids);
			foreach($data as $k=>$v){
				$v['addr']=$ods[$v['orderid']]['address'];
				$v['prolist']=$ods[$v['orderid']]['prolist'];
				$v["status_name"]=MM("f2c","f2c_order")->getStatus($v);
				$v["timeago"]=timeago(strtotime($v["createtime"]));
				$data[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$data,
			"type"=>$type
		));
		$this->smarty->display("f2c_order/my.html");
	}
	public function onShow(){
		$orderid=get("orderid","i");
		$order=MM("f2c","f2c_order")->selectRow("orderid=".$orderid);
		$orderdata=MM("f2c","f2c_order_data")->get($orderid);
		 
		if($order["teamid"]!=$this->team["teamid"]){
			$this->goAll("您无权查看当前订单",1);
		} 
		$order["status_name"]=MM("f2c","f2c_order")->getStatus($order);
		$order["timeago"]=timeago(strtotime($order["createtime"]));
		$this->smarty->goAssign(array(
			"order"=>$order,
			"addr"=>$orderdata["address"],
			"prolist"=>$orderdata["prolist"]
		));
		$this->smarty->display("f2c_team_order/show.html");
	}
	
}
