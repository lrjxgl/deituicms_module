<?php
class paotui_orderControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$typelist=MM("paotui","paotui")->typelist(); 
		$sql="select o.*,p.userid,p.toaddr,p.fromaddr,p.goodsmoney,p.weight,p.typeid,p.content 
			from ".table('mod_paotui_order')." as o
			left join ".table("mod_paotui")." as p
			on o.ptid=p.id
			where o.senderid=".SENDERID."
		";
		$type=get("type","h");
		switch($type){
			case "new":
				$sql.=" AND o.status=0 ";
				break;
			case "unsend":
				$sql.=" AND o.status=1 ";
				break;
			case "uncheck":
				$sql.=" AND o.status=2 ";
				break;
			case "finish":
				$sql.=" AND o.status=3 ";
				break;	
		}
		$sql.=" order by o.id DESC";
		$list=MM("paotui","paotui_order")->getAll($sql);
		if($list){
			foreach($list as $k=>$v){
				$v["status_name"]=MM("paotui","paotui_order")->getStatus($v["status"],$v["typeid]"]);
				
				$v['ispay_name']=$v['ispay']==2?"已支付":"未支付";
				$v["typeid_name"]=$typelist[$v["typeid"]]["title"];
				$v["fromaddr"]=json_decode($v["fromaddr"]);
				$v["toaddr"]=json_decode($v["toaddr"]);
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
		$this->smarty->display("paotui_order/index.html");
	}
	
	public function onShow(){
		$id=get("id","i");
		$sql="select o.*,p.userid,p.toaddr,p.fromaddr,p.goodsmoney,p.weight,p.typeid,p.content
			from ".table('mod_paotui_order')." as o
			left join ".table("mod_paotui")." as p
			on o.ptid=p.id
			where o.id=".$id."
		";
		$order=MM("paotui","paotui_order")->getRow($sql);
		
		$typelist=MM("paotui","paotui")->typelist(); 
		$order["status_name"]=MM("paotui","paotui_order")->getStatus($order["status"],$order["typeid]"]);
		
		$order['ispay_name']=$order['ispay']==2?"已支付":"未支付";
		$order["typeid_name"]=$typelist[$order["typeid"]]["title"];
		$order["fromaddr"]=json_decode($order["fromaddr"]);
		$order["toaddr"]=json_decode($order["toaddr"]);
		$this->smarty->goAssign(array(
			"order"=>$order
		));
		$this->smarty->display("paotui_order/show.html");
	}
	public function onConfirm(){
		$id=get("id","i");
		$order=M("mod_paotui_order")->selectRow("id=".$id);
		if($order["senderid"]!=SENDERID){
			$this->goAll("暂无权限",1);
		}
		M("mod_paotui_order")->update(array(
			"status"=>1,
			"updatetime"=>date("Y-m-d H:i:s"),
		),"id=".$id);
		//发送给用户消息
		M("notice")->add([
			"title"=>"跑腿任务提示",
			"content"=>"您的跑腿订单正在处理中...",
			"userid"=>$order["userid"],
			"linkurl"=>[
				"path"=>"/module.php",
				"m"=>"paotui",
				"a"=>"show",
				"param"=>"id=".$order["ptid"]
			]
			
		]);
		$this->goAll("正在办理中");
	}
	public function onSend(){
		$id=get("id","i");
		$order=M("mod_paotui_order")->selectRow("id=".$id);
		if($order["senderid"]!=SENDERID){
			$this->goAll("暂无权限",1);
		}
		M("mod_paotui")->update(array(
			"status"=>2,
			"updatetime"=>date("Y-m-d H:i:s"),
		),"id=".$order["ptid"]);
		M("mod_paotui_order")->update(array(
			"status"=>2
		),"id=".$id);
		//发送给用户消息
		M("notice")->add([
			"title"=>"跑腿任务提示",
			"content"=>"您的跑腿订单处理完成，快去确认一下吧",
			"userid"=>$order["userid"],
			"linkurl"=>[
				"path"=>"/module.php",
				"m"=>"paotui",
				"a"=>"show",
				"param"=>"id=".$order["ptid"]
			]
			
		]);
		$this->goAll("任务做完，待用户确认");
	}
	
}
?>