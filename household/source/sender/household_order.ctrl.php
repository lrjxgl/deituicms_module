<?php
class household_orderControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
	 
		$catid=get("catid","i");
		$catList=MM("household","household_category")->children(0); 
		$where=" status in(0,1,2,3) AND senderid=".SENDERID;
		$type=get("type","h");
		switch($type){
			case "new":
				$where.=" AND status=0 ";
				break;
			case "unsend":
				$where.=" AND  status=1 ";
				break;
			case "uncheck":
				$where.=" AND  status=2 ";
				break;
			case "finish":
				$where.=" AND status=3 ";
				break;	
		}
		$list=MM("household","household_order")->select(array(
			"where"=>$where,
			"order"=>"orderid DESC"
		));
		if($list){
			foreach($list as $v){
				$oids[]=$v["orderid"];
				$uids[]=$v["userid"];
			}
			$ods=MM("household","household_order_data")->getListByOrderIds($oids);
			$us=M("user")->getUserByIds($uids);
			foreach($list as $k=>$v){
				$v["cat_name"]="hi";
				$v['addr']=$ods[$v['orderid']]['address'];
				$v['prolist']=$ods[$v['orderid']]['prolist'];
				$v["status_name"]=MM("household","household_order")->getStatus($v);
				$v["timeago"]=timeago(strtotime($v["createtime"]));
				$v["nickname"]=$us[$v["userid"]]["nickname"];
				$v["status_name"]=MM("household","household_order")->getStatus($v);
				$list[$k]=$v;
			}
			 
		} 
		$typelist=array(
			["typeid"=>"new","title"=>"待去办"],
			["typeid"=>"unsend","title"=>"办理中"],
			["typeid"=>"uncheck","title"=>"待验收"],
			["typeid"=>"finish","title"=>"已完成"]
		);
		$this->smarty->goassign(array(
			"typelist"=>$typelist,
			"list"=>$list,
			"typeid"=>""
		));
		$this->smarty->display("household_order/index.html");
	}
	
	public function onShow(){
		$orerid=get("orerid","i");
		$orderid=get("orderid","i");
		$order=MM("household","household_order")->selectRow("orderid=".$orderid);
		$orderdata=MM("household","household_order_data")->get($orderid);
		 
		$order["status_name"]=MM("household","household_order")->getStatus($order);
		$order["timeago"]=timeago(strtotime($order["createtime"]));
		$logList=MM("household","household_order_log")->select(array(
			"where"=>"orderid=".$orderid,
			"order"=>"id ASC"
		));
		$append=M("mod_household_order_append")->selectRow("orderid=".$orderid);
		$hhconfig=M("mod_household_config")->selectRow("1");
		$this->smarty->goAssign(array(
			"order"=>$order,
			"addr"=>$orderdata["address"],
			"prolist"=>$orderdata["prolist"],
			"logList"=>$logList,
			"append"=>$append,
			"hhconfig"=>$hhconfig
		));
		$this->smarty->display("household_order/show.html");
	}
	public function onConfirm(){
		$orderid=get("orderid","i");
		$order=M("mod_household_order")->selectRow("orderid=".$orderid);
		if($order["senderid"]!=SENDERID){
			$this->goAll("暂无权限",1);
		}
		M("mod_household_order")->update(array(
			"status"=>1
		),"orderid=".$orderid);
		MM("household","household_order_log")->insert(array(
			"createtime"=>date("Y-m-d H:i:s"),
			"content"=>"师傅正在处理中..",
			"orderid"=>$order["orderid"],
			"utype"=>"sender",
			"userid"=>$order["senderid"]
		));
		//通知用户
		M("notice")->add(array(
			"content"=>"您的家政订单,师傅正在处理",
			"userid"=>$order["userid"],
			"linkurl"=>array(
				"m"=>"household_order",
				"a"=>"show",
				"param"=>"orderid=".$order["orderid"],
				"path"=>"/module.php"
			)
		));
		$this->goAll("正在办理中");
	}
	public function onSend(){
		$orderid=get("orderid","i");
		$order=M("mod_household_order")->selectRow("orderid=".$orderid);
		if($order["senderid"]!=SENDERID){
			$this->goAll("暂无权限",1);
		}
		 
		M("mod_household_order")->update(array(
			"status"=>2
		),"orderid=".$orderid);
		MM("household","household_order_log")->insert(array(
			"createtime"=>date("Y-m-d H:i:s"),
			"content"=>"任务做完，待用户确认",
			"orderid"=>$order["orderid"],
			"utype"=>"sender",
			"userid"=>$order["senderid"]
		));
		M("notice")->add(array(
			"content"=>"您的家政订单,师傅处理完成，尽快确认一下吧",
			"userid"=>$order["userid"],
			"linkurl"=>array(
				"m"=>"household_order",
				"a"=>"show",
				"param"=>"orderid=".$order["orderid"],
				"path"=>"/module.php"
			)
		));
		$this->goAll("任务做完，待用户确认");
	}
	
}
?>