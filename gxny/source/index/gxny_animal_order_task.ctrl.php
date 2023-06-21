<?php
class gxny_animal_order_taskControl extends skymvc{
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
		$data=MM("gxny","gxny_animal_order_task")->select($option,$rscount);
		 
		if(!empty($data)){
			$shopids=[];
			$statusList=MM("gxny","gxny_animal_order_task")->statusList();
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
		$this->smarty->display("gxny_animal_order_task/my.html");
	}
	
	public function onSave(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$orderid=get_post("orderid","i");
		$content=post("content","h");
		$tablename=post("tablename","h");
		if($tablename=='order'){
			$order=M("mod_gxny_order")->selectRow("orderid=".$orderid);
		}else{
			$order=M("mod_gxny_animal_order")->selectRow("orderid=".$orderid);
		}
		
		$time=date("Y-m-d H:i:s");
		M("mod_gxny_animal_order_task")->insert(array(
			"tablename"=>$tablename,
			"orderid"=>$orderid,
			"animalid"=>$order["animalid"],
			"userid"=>$userid,
			"shopid"=>$order["shopid"],
			"createtime"=>$time,
			"updatetime"=>$time,
			"content"=>$content,
			"task_action"=>"拍视频"
		));
		$this->goAll("任务发布成功");
	}
}