<?php
class csc_order_sendControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$this->smarty->display("csc_order_send/index.html");
	}
	public function onApi(){
		$where=" shopid=".SHOPID;
		$url="/moduleshop.php?m=csc_order";
		$limit=24;
		$start=get("per_page","i");
		$where.=" AND status in(0,1) AND ispay=1 AND senderid=0 ";
		 
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" orderid DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=MM("csc","csc_order")->select($option,$rscount);
		if($data){
			foreach($data as $v){
				$oids[]=$v["orderid"];
			}
			$ods=MM("csc","csc_order_data")->getListByOrderIds($oids);
			foreach($data as $k=>$v){
				$v['addr']=$ods[$v['orderid']]['address'];
				$v['prolist']=$ods[$v['orderid']]['prolist'];
				$v["status_name"]=MM("csc","csc_order")->getStatus($v);
				$v["timeago"]=timeago(strtotime($v["createtime"]));
				$data[$k]=$v;
			}
		}
		$sds=M("mod_csc_sender")->select(array(
			"where"=>" shopid=".SHOPID." AND status=1 "
		));
		if($sds){
			foreach($sds as $k=>$sd){
				$ct=M("mod_csc_order")->selectOne(array(
					"where"=>" senderid=".$sd["senderid"]." AND status<3 ",
					"fields"=>" count(*) as  ct"
				));
				$sd["have_num"]=$ct;
				$sds[$k]=$sd;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$data,
			"type"=>$type,
			"sds"=>$sds
		));
	}
	
	public function onfenpei(){
		$orderid=get("orderid","i");
		$senderid=get("senderid","o");
		$order=M("mod_csc_order")->selectRow("orderid=".$orderid);
		if(!$order["ispay"]){
			$this->goAll("还未支付，无法分配",1);
		}
		$shop=M("mod_csc_shop")->selectRow(array(
			"where"=>"shopid=".$order["shopid"],
			"fields"=>"shopid,sender_money,sender_plan_money"
		));
		M("mod_csc_order")->begin();
		M("mod_csc_order")->update(array(
			"senderid"=>$senderid
		),"orderid=".$orderid);
		M("mod_csc_sender_order")->insert(array(
			"senderid"=>$senderid,
			"orderid"=>$orderid,
			"stime"=>time(),
			
			"shopid"=>$order["shopid"],
			"money"=>$order["isplan"]?$shop["sender_plan_money"]:$shop["sender_money"]
		));
		M("mod_csc_order")->commit();
		$this->goAll("分配成功");
	}
	
}
