<?php
class gxny_order_taskControl extends skymvc{
	
	public function onDefault(){
		
	}
	
	public function onMy(){
		M("login")->checkLogin();
		 
		$userid=M("login")->userid;
		$where=" userid=".$userid;
		$url="/module.php?m=gxny_order_task&a=my";
		$type=get("type","h");
		switch($type){
			case "new":
				$where .=" AND status in(0,1)";
				break;
			case "receive":
				$where.=" AND status=2 ";
				break;
			case "finish":
				$where.=" AND status=3 ";
				break;
			default:
				$where .=" AND status in(0,1,2,3) ";
				break;
		} 
		$limit=20;
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" taskid DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=MM("gxny","gxny_order_task")->select($option,$rscount);
		 
		if(!empty($data)){
			$shopids=[];
			$statusList=MM("gxny","gxny_order_task")->statusList();
			foreach($data as $k=>$v){
				$shopids[]=$v["shopid"];
			}
			$sps=MM("gxny","gxny_shop")->getListByIds($shopids);
			foreach($data as $k=>$v){
				$v["shopname"]=$sps[$v["shopid"]]["shopname"];
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
				"url"=>$url,
				"type"=>$type,
				"catList"=>$catList
			)
		);
		$this->smarty->display("gxny_order_task/my.html");
	}
	
	/**
	 * 种菜
	 */
	public function onZhongcai(){
		$id=get_post("id","i");
		$pro=MM("gxny","gxny_shop_product")->selectRow("id=".$id);
		if(empty($pro) || $pro["iszhong"]==1){
			$this->goAll("无法种植",1);
		}
		$caidans=post("caidans","h");
		if(empty($caidans)){
			$this->goAll("请选择蔬菜",1);
		}
		if(!is_array($caidans)){
			$caidans=explode(",",$caidans);
		}
		$cat=MM("gxny","gxny_shop_category")->selectRow("catid=".$pro["catid"]);
		$cds=explode(",",$cat["caidan"]);
		foreach($caidans as $cd){
			if(!in_array($cd,$cds)){
				$this->goAll($cd."当前不能种植",1);
			}
		}
		$cdstr=implode(",",$caidans);
		$content="在#".$pro["no"]."菜园种植：".$cdstr;
		MM("gxny","gxny_order_task")->insert(array(
			"productid"=>$pro["id"],
			"orderid"=>$pro["orderid"],
			"userid"=>$pro["userid"],
			"shopid"=>$pro["shopid"],
			"createtime"=>date("Y-m-d H:i:s"),
			"updatetime"=>date("Y-m-d H:i:s"),
			"typeid"=>1,
			"task_action"=>"种植",
			"content"=>$content
		));
		MM("gxny","gxny_shop_product")->update(array(
			"caidan"=>$cdstr,
			"iszhong"=>1
		),"id=".$pro["id"]);
		$this->goAll("播种任务下单成功");
		
	}
	
	public function onWeihu(){
		$id=get_post("id","i");
		$pro=MM("gxny","gxny_shop_product")->selectRow("id=".$id);
		if(empty($pro) ){
			$this->goAll("无法维护",1);
		}
		 
		$cdstr=post("weihu","h");
		$task=MM("gxny","gxny_order_task")->selectRow("productid=".$pro["id"]." AND task_action='".$cdstr."' AND status=0 ");
		if(!empty($task)){
			$this->goAll("最近刚下单",1);
		}
		$content="在#".$pro["no"]."菜园维护：".$cdstr;
		MM("gxny","gxny_order_task")->insert(array(
			"productid"=>$pro["id"],
			"orderid"=>$pro["orderid"],
			"userid"=>$pro["userid"],
			"shopid"=>$pro["shopid"],
			"createtime"=>date("Y-m-d H:i:s"),
			"updatetime"=>date("Y-m-d H:i:s"),
			"typeid"=>2,
			"task_action"=>$cdstr,
			"content"=>$content
		));
		 
		$this->goAll("维护任务下单成功");
	}
	
	public function onCaizhai(){
		$id=get_post("id","i");
		$pro=MM("gxny","gxny_shop_product")->selectRow("id=".$id);
		if(empty($pro) ){
			$this->goAll("无法维护",1);
		}
		 
		$cdstr="采摘";
		$task=MM("gxny","gxny_order_task")->selectRow("productid=".$pro["id"]." AND task_action='采摘' AND status=0 ");
		if(!empty($task)){
			$this->goAll("最近刚下单",1);
		}
		$content=post("content","h");
		$user_address_id=post("user_address_id","i");
		$addr=M("user_address")->selectRow("id=".$user_address_id);
		if(empty($addr)){
			$this->goAll("请填写联系方式",1);
		}
		$contact="<div>".$addr["nickname"]." ".$addr["telephone"]."</div><div>".$addr["pct_addr"]."</div>";
		$content="在#".$pro["no"]."菜园采摘配送：<br />".$content."<br />".$contact;
		MM("gxny","gxny_order_task")->insert(array(
			"productid"=>$pro["id"],
			"orderid"=>$pro["orderid"],
			"userid"=>$pro["userid"],
			"shopid"=>$pro["shopid"],
			"createtime"=>date("Y-m-d H:i:s"),
			"updatetime"=>date("Y-m-d H:i:s"),
			"typeid"=>3,
			"task_action"=>$cdstr,
			"content"=>$content
		));
		 
		$this->goAll("采摘任务下单成功");
	}
	
}
